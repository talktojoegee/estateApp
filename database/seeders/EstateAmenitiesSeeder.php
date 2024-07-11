<?php

namespace Database\Seeders;

use App\Models\EstateAmenity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstateAmenitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $amenities = [
            'Meet FCT infrastructure',
            'Good roads',
            'Electricity surface',
            'Asphalt road network',
            'Water supply',
            'Recreation',
            'Police outpost',
            'Shopping area',
            'Worship centers',
            'Street lights',
            'Security',
            'Corner shops',
            'Artificial lakes',
            'Commercial area',
            'Public water supply',
        ];
        foreach ($amenities as $amenity){
            EstateAmenity::create(['ea_name'=>$amenity]);
        }
    }
}
