<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Receipt extends Model
{
    use HasFactory;

    public function getLastReceipt(){
        return Receipt::orderBy('id', 'DESC')->first();
    }

    public function getIssuedBy(){
        return $this->belongsTo(User::class, 'issued_by');
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
        return substr(sha1(time()),32,40);
    }
}
