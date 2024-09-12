<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConstructionStage extends Model
{
    use HasFactory;
protected $primaryKey = 'cs_id';
    protected $fillable = ['cs_name', 'cs_id'];

    public static function getConstructionStages(){
        return ConstructionStage::all();
    }

    public static function getConstructionStageByName($name){
        return ConstructionStage::where('cs_name', $name)->first();
    }
}
