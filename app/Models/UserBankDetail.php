<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class UserBankDetail extends Model
{
    use HasFactory;



    public function getUserBankDetail(){
        //return $this->belongsTo();
    }


    public function addBankDetails(Request $request, $userId){
        $record = new UserBankDetail();
        $record->user_id = $userId;
        $record->bank_name = $request->bankName ?? null;
        $record->account_name = $request->accountName ?? null;
        $record->account_no = $request->accountNumber ?? null;
        $record->tax_id = $request->taxID ?? null;
        $record->retirement_savings = $request->retirementSavings ?? null;
        $record->pension_fund = $request->pensionFund ?? null;
        $record->save();
        return $record;
    }


}
