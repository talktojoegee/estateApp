<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Receipt extends Model
{
    use HasFactory;

    public function getLastReceipt(){
        return Receipt::orderBy('id', 'DESC')->first();
    }

    public function getProperty(){
        return $this->belongsTo(Property::class, 'property_id');
    }
    public function getIssuedBy(){
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function getCustomer(){
        return $this->belongsTo(Lead::class, 'customer_id');
    }
    public function getInvoice(){
        return $this->belongsTo(InvoiceMaster::class, 'invoice_id');
    }


    public function generateReceipt(Request $request, $invoice){
        $last_receipt = $this->getLastReceipt();
        $counter = null;
        if(!empty($last_receipt)){
            $counter = $last_receipt->receipt_no + 1;
        }else{
            $counter = 100000;
        }

        #If it is new lease, register applicant as tenant then schedule lease on pending mode
        //if($invoice->invoice_type == 1){ //new lease
            #Applicant
            //$this->updateInvoiceTenantId($invoice, $invoice->user_id);
            $this->updateTenantLeaseApplicationStatusById($invoice->applicant_id, 3); //paid
            #Enlist for schedule
            $this->updatePropertyListingStatusAsClosed($invoice);
            $this->createNewReceipt($counter, $invoice, $request->amount, 0);

            // }
        //}

    }

    public function createNewReceipt($counter, $invoice, $amount, $charge){
        $receipt = new Receipt;
        $receipt->receipt_no = $counter;
        $receipt->invoice_id = $invoice->id;
        $receipt->property_id = $invoice->property_id;
        $receipt->customer_id = $invoice->customer_id ?? null; //client | resident
        //$receipt->applicant_id = $invoice->invoice_type == 1 ? $invoice->applicant_id : '';
        $receipt->payment_method = $invoice->payment_method ?? 1; //cash
        $receipt->payment_date = now();
        $receipt->trans_ref = $this->generateTransactionRef();
        $receipt->total = ($amount) ?? 0;
        $receipt->sub_total = ($amount - $charge) ?? 0;
        //$receipt->receipt_type = $invoice->invoice_type ?? 1;
        //$receipt->tenant_id = $invoice->tenant_id;
        $receipt->issued_by = Auth::user()->id;
        $receipt->save();
    }


    public function generateTransactionRef(){
        return substr(sha1((time()+ rand(99,999))),32,40);
    }

    public function getAllTenantReceipts(){
        return  Receipt::orderBy('id', 'DESC')->get();
    }
    public function getReceiptsByPropertyIds($propertyIds){
        return  Receipt::whereIn('property_id', $propertyIds)->orderBy('id', 'DESC')->get();
    }

    public function getAllTenantReceiptsThisYear(){
        return  Receipt::whereYear('created_at', date('Y'))->orderBy('id', 'DESC')->get();
    }


    public function getReceipt($slug){
        $receipt = Receipt::where('trans_ref', $slug)->first();
        return $receipt;
    }


    public function sendReceiptAsEmail($slug){
        $receipt = Receipt::where('trans_ref', $slug)->first();
        #Email task
    }

    public function getLatestReceipt(){
        return Receipt::orderBy('id', 'DESC')->first();
    }

    public function getTotalSalesByDateRange($startDate, $endDate){
        return Receipt::select(
            DB::raw("DATE_FORMAT(payment_date, '%m-%Y') monthYear"),
            DB::raw("YEAR(payment_date) year, MONTH(payment_date) month"),
            DB::raw("SUM(sub_total) total"),
            'payment_date',
        )->whereBetween('payment_date', [$startDate, $endDate])
            ->orderBy('month', 'ASC')
            ->groupby('year','month')
            ->get();
    }


    public function getListOfPropertiesSoldRange($start, $end){
        return Receipt::select(
            'property_id',
            'payment_date',
            'issued_by'
        )->whereBetween('payment_date', [$start, $end])
            //->orderBy('month', 'ASC')
            ->groupby('property_id')
            ->get();
    }
}
