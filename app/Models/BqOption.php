<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BqOption extends Model
{
    use HasFactory;
    protected  $primaryKey = 'bqo_id';
    protected $fillable = ['bqo_name', 'bqo_id'];
    public static function getBQOptions(){
        return BqOption::all();
    }
}
