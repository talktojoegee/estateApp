<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentPlan extends Model
{
    use HasFactory;
    protected $fillable = [
        'pp_name',
        'pp_rate',
        'pp_type',
        'pp_description'
    ];


    public static function getPaymentPlans(){
        return PaymentPlan::all();
    }


}
