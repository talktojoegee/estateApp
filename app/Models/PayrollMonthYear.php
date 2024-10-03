<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PayrollMonthYear extends Model
{
    use HasFactory;


    public static function addPayrollMonthYear($month, $year){
        $record = new PayrollMonthYear();
        $record->payroll_month = $month ?? null;
        $record->payroll_year = $year ?? null;
        $record->payroll_status = 1;
        $record->save();
    }


    public static function getPayrollMonthYear(){
        return PayrollMonthYear::all();
    }

    public static function getActivePayrollMonthYear(){
        return PayrollMonthYear::where('payroll_status', 1)->first();
    }

    public static function getPayrollMonthYearById($id){
        return PayrollMonthYear::find($id);
    }


}
