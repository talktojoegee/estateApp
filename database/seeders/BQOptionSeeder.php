<?php

namespace Database\Seeders;

use App\Models\BqOption;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BQOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
$options = ['None',
            "Inbuilt: self-contain (a room with toilet/bathroom and kitchen).",
            "Externally built: 1 unit of self-contain (a room with toilet/bathroom and kitchen).",
            "Externally built: 2 units of self-contain (a room with toilet/bathroom and kitchen).",
            "Inbuilt: 1-bedroom (sitting room, a room with toilet/bathroom and kitchen).",
            "Externally built: 1-bedroom (sitting room, a room with toilet/bathroom and kitchen).",
            "Externally built: 2 units of 1-bedroom (sitting room, a room with toilet/bathroom and kitchen).",
            "with space for 2 rooms BQ",
        ];
    foreach($options as $option){
        BqOption::create(['bqo_name'=>$option]);
    }
    }
}
