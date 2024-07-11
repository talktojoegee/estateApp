<?php

namespace Database\Seeders;

use App\Models\BuildingType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BuildingTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            'None',
            'Detached Duplex',
            'Duplex(Detached)',
            'Semi-detached Duplex',
            'Terraced Duplex',
            'Bungalow(Detached)',
            'Semi-detached Bungalow',
            'Terraced Bungalow',
            'Block of flats',
            ];
        foreach($types as $type){
            BuildingType::create(['bt_name'=>$type, 'bt_slug'=>Str::slug($type)]);
        }
    }
}
