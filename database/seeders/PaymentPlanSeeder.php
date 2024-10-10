<?php

namespace Database\Seeders;

use App\Models\PaymentPlan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $list = [
          'None',
          'Outright purchase',
          'Conditional Purchase',
          'NHF',
          'Smart Homes',
          'Rent to Own'
        ];
        $desc = [
          null,
          null,
          'Pay 70% and take possession, and pay the balance of 30% within 90 days (3 months period).',
          'Pay 30% down payment and process the balance through FGMB within the above mentioned Scheme. Balance can be paid within 10 to 30 years depending on your unexpired service years.',
          'Make 50% down payment and take possession, take advantage of an in-house mortgage provider who will pay us the balance at 10% interest to you; repayable in 6(six) years.',
          'Pay your annual rent for 13 years and own the house. Take possession from point of payment of year one.'
        ];
        $rates = [
          100,100,70,30,50,10
        ];
        foreach($list as $key => $name){
            PaymentPlan::create([
                'pp_name'=>$name,
                'pp_description'=>$desc[$key] ?? null,
                'pp_rate'=>$rates[$key],
                'pp_type'=>1
            ]);
        }
    }
}
