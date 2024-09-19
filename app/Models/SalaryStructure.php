<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SalaryStructure extends Model
{
    use HasFactory;

    public function getAllowances(){
        return $this->hasMany(SalaryAllowance::class, 'salary_structure_id');
    }


    public function addSalaryStructure(Request $request){
        $record = new SalaryStructure();
        $record->ss_name = $request->name ?? null;
        $record->ss_status = 1;
        $record->slug = Str::slug($request->name).'-'.substr(sha1(time()),29,40);
        $record->save();
        return $record;
    }
    public function updateSalaryStructure(Request $request){
        $record =  SalaryStructure::find($request->structure);
        $record->ss_name = $request->name ?? null;
        $record->ss_status = 1;
        $record->save();
        return $record;
    }

    public function getSalaryStructures(){
        return SalaryStructure::orderBy('ss_name', 'ASC')->get();
    }
    public function getSalaryStructureBySlug($slug){
        return SalaryStructure::where('slug',$slug)->first();
    }
}
