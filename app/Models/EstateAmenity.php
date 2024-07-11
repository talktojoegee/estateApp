<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstateAmenity extends Model
{
    use HasFactory;

    protected $primaryKey = 'ea_id';
    protected $fillable = ['ea_name'];


    public function getAllEstateAmenities(){
        return EstateAmenity::all();
    }
}
