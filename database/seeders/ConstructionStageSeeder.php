<?php

namespace Database\Seeders;

use App\Models\ConstructionStage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConstructionStageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stages = [
            "None",
            "Virgin plot",
            "DPC",
            "Blockwork",
            "First floor blockwork",
            "Other floors blockwork",
            "Roofed/carcass",
            "Completed",
            ];
        foreach($stages as $stage){
            ConstructionStage::create(['cs_name'=>$stage]);
        }
    }
}
