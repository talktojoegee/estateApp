<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuildingType extends Model
{
    use HasFactory;
    protected $primaryKey = 'bt_id';
    protected $fillable = ['bt_name', 'bt_id', 'bt_slug'];

    public static function getBuildingTypes(){
        return BuildingType::all();
    }
}
