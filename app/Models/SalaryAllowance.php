<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalaryAllowance extends Model
{
    use HasFactory;

    public function getPayment(){
        return $this->belongsTo(PaymentDefinition::class, 'payment_definition_id');
    }

    public function getCategory(){
        return $this->belongsTo(SalaryStructure::class, 'salary_structure_id');
    }


    public function addSalaryAllowance($structureId, $definitionId, $amount){
        $allowance = new SalaryAllowance();
        $allowance->salary_structure_id = $structureId;
        $allowance->payment_definition_id = $definitionId;
        $allowance->amount = $amount;
        $allowance->added_by = Auth::user()->id;
        $allowance->save();
    }

    public function updateSalaryAllowance($allowanceId, $structureId, $definitionId, $amount){
        $allowance =  SalaryAllowance::find($allowanceId);
        $allowance->salary_structure_id = $structureId;
        $allowance->payment_definition_id = $definitionId;
        $allowance->amount = $amount;
        $allowance->added_by = Auth::user()->id;
        $allowance->save();
    }

    public function removeAllowance($allowanceId){
        $allowance = SalaryAllowance::find($allowanceId);
        $allowance->delete();
    }


    public function getAllowances(){
        return SalaryAllowance::all();
    }
}
