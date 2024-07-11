<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Coa extends Model
{
    use HasFactory;
    protected $primaryKey = 'coa_id';
    protected $fillable = [
      'account_name', 'account_type', 'bank', 'glcode', 'parent_account', 'type', 'note'
    ];



    public function addAccount(Request $request){
        return Coa::create($request->all());
    }

    public function getAllChartOfAccounts(){
        return Coa::all();
    }
    public function getAllChartOfAccountsByType(){
        return Coa::where('type', 1)->get();
    }


    public function getChartOfAccountByAccountType($type, $account_type){
        return Coa::select('account_name', 'coa_id', 'type', 'glcode')
            ->where('type',$type)
            ->where('account_type',$account_type)
            ->get();
    }


    public function getParentAccountByGlcode($glcode){
        return Coa::where('glcode', $glcode)->first();
    }
}
