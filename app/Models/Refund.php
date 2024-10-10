<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Refund extends Model
{
    use HasFactory;

    public function getRequestedBy(){
        return $this->belongsTo(User::class, 'requested_by');
    }
    public function getActionedBy(){
        return $this->belongsTo(User::class, 'actioned_by');
    }
    public function getReceipt(){
        return $this->belongsTo(Receipt::class, 'receipt_id');
    }

    public function addRefund(Request $request, $amountPaid, $actualAmount, $amountRefunded){
        $refund = new Refund();
        $refund->receipt_id = $request->receipt;
        $refund->requested_by = Auth::user()->id;
        $refund->date_requested = $request->dateRequested ?? now();
        $refund->amount_paid = $amountPaid;
        $refund->actual_amount = $actualAmount;
        $refund->amount_refunded = $amountRefunded;
        $refund->refund_rate = $request->rate;
        $refund->save();
    }

    public function getAllRefundRequests(){
        return Refund::orderBy('id', 'DESC')->get();
    }
    public static function getRefundById($id){
        return Refund::find($id);
    }

    public function getAllRefundsByStatus($status){
        return  Refund::where('status',$status)->orderBy('id', 'DESC')->get();
    }

    public function getAllRefundsThisYear($status){
        return  Refund::where('status', $status)->whereYear('date_actioned', date('Y'))->orderBy('id', 'DESC')->get();
    }

    public function getLastYearRefunds($status){
        return  Refund::where('status', $status)->whereYear('date_actioned', date('Y') - 1)->orderBy('id', 'DESC')->get();
    }

    public function getCurrentMonthRefunds($status){
        return Refund::where('status', $status)->whereMonth('date_actioned', date('m'))
            ->whereYear('date_actioned', date('Y'))->orderBy('id', 'DESC')->get();
    }
    public function getLastMonthRefunds($status){
        $currentMonth = date('m');
        $lastMonth = $currentMonth - 1;
        if($lastMonth == 0){
            $lastMonth = 12;
        }
        return Refund::where('status', $status)->whereMonth('date_actioned', $lastMonth)
            ->whereYear('date_actioned', date('Y'))->orderBy('id', 'DESC')->get();
    }

}
