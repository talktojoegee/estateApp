<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SalaryStructurePersonalized extends Model
{
    use HasFactory;

    public function getPaymentDefinition(){
        return $this->belongsTo(PaymentDefinition::class, 'payment_definition_id');
    }


    public function addSalaryAllowance($employeeId, $definitionId, $amount){
        $allowance = new SalaryStructurePersonalized();
        $allowance->employee_id = $employeeId;
        $allowance->payment_definition_id = $definitionId;
        $allowance->amount = $amount;
        $allowance->added_by = Auth::user()->id;
        $allowance->save();
    }

    public static function getEmployeePersonalizedStructure($employeeId){
        return SalaryStructurePersonalized::where('employee_id',$employeeId)->get();
    }
}
