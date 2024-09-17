<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentDefinition extends Model
{
    use HasFactory;


    public function addPaymentDefinition(Request $request){
        $payment = new PaymentDefinition();
        $payment->pay_code = $request->payCode ?? null;
        $payment->payment_name = $request->paymentName ?? null;
        $payment->taxable = $request->taxable ?? null;
        $payment->payment_type = $request->paymentType ?? null;
        $payment->payment_variance = $request->paymentVariance ?? null;
        $payment->added_by = Auth::user()->id;
        $payment->save();
        return $payment;
    }
    public function updatePaymentDefinition(Request $request){
        $payment =  PaymentDefinition::find($request->definition);
        $payment->pay_code = $request->payCode ?? null;
        $payment->payment_name = $request->paymentName ?? null;
        $payment->taxable = $request->taxable ?? null;
        $payment->payment_type = $request->paymentType ?? null;
        $payment->payment_variance = $request->paymentVariance ?? null;
        //$payment->added_by = Auth::user()->id;
        $payment->save();
        return $payment;
    }

    public function getPaymentDefinitionList(){
        return PaymentDefinition::all();
    }


    public function getPaymentDefinitionListByType($type){
        return PaymentDefinition::where('payment_type', $type)->get();
    }

    public function getPaymentDefinitionListByVariance($var){
        return PaymentDefinition::where('payment_variance', $var)->get();
    }


}
