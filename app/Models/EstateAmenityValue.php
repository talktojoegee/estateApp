<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstateAmenityValue extends Model
{
    use HasFactory;
    protected $primaryKey = 'eav_id';
    protected $fillable = ['eav_estate_id', 'eav_amenity_id', 'eav_value'];


}
