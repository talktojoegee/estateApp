<?php

namespace Database\Seeders;

use App\Models\PropertyTitle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PropertyTitleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $titles = [
            "None",
            "Letter of Allocation",
            "Certificate of Occupancy (C-of-O)",
            "Right of Occupancy (R-of-O)",
            "Provisional Letter",
        ];
        foreach($titles as $title){
            PropertyTitle::create(['pt_name'=>$title]);
        }
    }
}
