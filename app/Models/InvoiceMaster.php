<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvoiceMaster extends Model
{
    use HasFactory;

    public function getInvoiceDetail(){
        return $this->hasMany(InvoiceDetail::class, 'invoice_id');
    }

    public function getAuthor(){
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function getCompany(){
        return $this->belongsTo(Organization::class, 'org_id');
    }
    public function getCustomer(){
        return $this->belongsTo(Lead::class, 'customer_id');
    }

    public function getProperty(){
            return $this->belongsTo(Property::class, 'property_id');
   }


    public function getInvoicePaymentLog(){
        return $this->hasMany(InvoicePaymentHistory::class, 'invoice_id');
    }

    public function getAllInvoiceReceipts(){
        return $this->hasMany(Receipt::class, 'invoice_id');
    }


    public function getActionedBy(){
        return $this->belongsTo(User::class, 'actioned_by');
    }


    public function createNewInvoice(Request $request, $invoiceNo){
        $invoice = new InvoiceMaster();
        $invoice->post_id = $request->postId;
        $invoice->generated_by = Auth::user()->id;
        $invoice->org_id = 1;// $post->p_org_id;
        $invoice->total = $this->getTotal($request);
        $invoice->ref_no = substr(sha1(time()),30,40);
        $invoice->save();
        return $invoice;
    }

    public function generateInvoice($propertyId, $customerId, $invoiceNo,
                                    $invoiceType,$issueDate,$dueDate,$subTotal,
                                    $total, $amount_paid,$status, $service, $quantity, $amount){
        $invoice = new InvoiceMaster();
        $invoice->property_id = $propertyId;
        $invoice->customer_id = $customerId;
        $invoice->invoice_no = $invoiceNo;
        $invoice->ref_no = substr(sha1((time() + $invoiceNo)),32,40);
        $invoice->invoice_type = $invoiceType;
        $invoice->issue_date = $issueDate;
        $invoice->due_date = $dueDate;
        $invoice->sub_total = $subTotal ?? 0;
        $invoice->total = $total ?? 0;
        $invoice->amount_paid = $amount_paid ?? 0;
        $invoice->generated_by = Auth::user()->id;
        $invoice->slug = substr(sha1( (time() + $invoiceNo) ), 21,40);
        $invoice->status = $status;
        $invoice->save();
        #Recent invoice ID
        $invoiceId = $invoice->id;
        $this->generateInvoiceItems($service, $quantity, $amount, $invoiceId);
        return $invoice;
    }
    public function generateInvoiceItems($service, $quantity = 1, $amount, $invoiceId){
        $item = new InvoiceDetail();
        $item->invoice_id = $invoiceId;
        $item->item_id = 1; //no in use
        $item->description = $service;
        $item->quantity = $quantity;
        $item->unit_cost = $amount;
        $item->amount = $quantity * $amount;
        $item->save();

    }


    public function newCustomerInvoice(Request $request, $invoice_no){
        $invoice = new InvoiceMaster();
        $invoice->property_id = $request->property;
        $invoice->customer_id = $request->customer;
        $invoice->invoice_no = !empty($invoice_no) ? $invoice_no->invoice_no + 1 : 100000;
        $invoice->ref_no = substr(sha1(time()),32,40);
        $invoice->invoice_type = $request->invoice_type;
        $invoice->issue_date = $request->issue_date;
        $invoice->due_date = $request->due_date;
        $invoice->sub_total = $this->invoiceServiceTotal($request);
        $invoice->total = $this->invoiceServiceTotal($request);
        $invoice->generated_by = Auth::user()->id;
        $invoice->slug = substr(sha1(time()), 21,40);
        $invoice->save();
        #Recent invoice ID
        $invoiceId = $invoice->id;
        $this->registerInvoiceItems($request, $invoiceId);
        return $invoice;
    }


    public function registerInvoiceItems(Request $request, $invoiceId){
        for($i = 0; $i<count($request->service); $i++){
            $item = new InvoiceDetail();
            $item->invoice_id = $invoiceId;
            $item->item_id = 1; //no in use
            $item->description = $request->service[$i];
            $item->quantity = $request->quantity[$i];
            $item->unit_cost = $request->amount[$i];
            $item->amount = $request->quantity[$i] * $request->amount[$i];
            $item->save();
        }
    }

    public function invoiceServiceTotal(Request $request){
        $total = 0;
        for($i = 0; $i<count($request->service); $i++){
            $total += ($request->amount[$i] * $request->quantity[$i]);
        };
        return $total;
    }

    public function getTotal($request){
        $total = 0;
        foreach($request->quantity as $key => $quantity){
            $total += ($quantity * $request->rate[$key]);
        }
        return $total;
    }

    public function getInvoiceByRefNo($refNo){
        return InvoiceMaster::where('ref_no',$refNo)->first();
    }

    public function getAllInvoices($status){
        return InvoiceMaster::whereIn('status', $status)->orderBy('id', 'DESC')->get();
    }

    public function getAllCompanyInoices($orgId, $status){
        return InvoiceMaster::where('org_id', $orgId)->where('status', $status)->orderBy('id', 'DESC')->get();
    }

    public function getInoviceById($id){
        return InvoiceMaster::find($id);
    }

    public function getLatestInvoiceNo(){
        return InvoiceMaster::orderBy('id', 'DESC')->first();
    }

    public function updateInvoicePayment(InvoiceMaster $invoice, $amount){
        $invoice->amount_paid += ($amount) ?? 0;
        $invoice->status = $invoice->amount_paid >= $invoice->total  ? 1 : 2; //0=pending,1=fully-paid,2=partly-paid, 3=declined
        $invoice->save();
        return $invoice;
    }


    public function getTotalInvoiceByDateRange($startDate, $endDate){
        return InvoiceMaster::select(
            DB::raw("DATE_FORMAT(issue_date, '%m-%Y') monthYear"),
            DB::raw("YEAR(issue_date) year, MONTH(issue_date) month"),
            DB::raw("SUM(sub_total) total"),
            'issue_date',
        )->whereBetween('issue_date', [$startDate, $endDate])
            //->where('a_branch_id', Auth::user()->branch)
            ->orderBy('month', 'ASC')
            ->groupby('year','month')
            ->get();
    }
}
