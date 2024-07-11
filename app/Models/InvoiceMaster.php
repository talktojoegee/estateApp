<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
