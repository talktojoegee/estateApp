<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyTitle extends Model
{
    use HasFactory;

    protected $fillable = ['pt_name', 'pt_id'];
    protected $primaryKey = 'pt_id';

    public static function getPropertyTitles(){
        return PropertyTitle::all();
    }

    public static function getPropertyTitleByName($name){
        return PropertyTitle::where('pt_name',$name)->first();
    }
}
