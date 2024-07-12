<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicePaymentHistory extends Model
{
    use HasFactory;


    public function logPayment($invoiceId, $tenantId, $amount, $charge){
        $log = new InvoicePaymentHistory();
        $log->tenant_id = 1;
        $log->invoice_id = $invoiceId;
        $log->amount = $amount;
        $log->charge = $charge;
        $log->save();
    }
}
