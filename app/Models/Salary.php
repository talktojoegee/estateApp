<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Salary extends Model
{

    public function getEmployee(){
        return $this->belongsTo(User::class, 'employee_id');
    }


    public function getPaymentDefinition(){
        return $this->belongsTo(PaymentDefinition::class, 'payment_definition_id');
    }


    use HasFactory;
    protected $fillable = [
        'paid_by',
        'employee_id',
        'payment_definition_id',
        'payroll_month',
        'payroll_year',
        'amount',
        'status',
        'date_actioned',
        'actioned_by',
        'batch_code',
    ];


    public static function getPendingSalary(){
        return Salary::where('status',0)->get();
    }

    public static function getPayslip($month, $year, $empId){
        return Salary::where('payroll_month',$month)->where('payroll_year', $year)
            ->where('employee_id', $empId)->where('status',1)->get();
    }

    public static function getDistinctPendingSalary(){
        return Salary::where('status',0)->groupBy('employee_id')->get();
    }


    public function getGrossPay($employeeId){
        return Salary::where('employee_id', $employeeId)->sum('amount');
    }

    public function getDeduction($employeeId){
        return DB::table('salaries as s')
            ->join('payment_definitions as p', 's.payment_definition_id', '=', 'p.id')
            ->where('s.employee_id', $employeeId )
            ->where('p.payment_type', 2 )
            //->select( DB::raw('SUM(s.amount) as amount'));
            ->sum( 's.amount');
            //->get();
    }

    public static function getSalaryByBatchCode($batchCode){
        return Salary::where('batch_code', $batchCode)->get();
    }
}
