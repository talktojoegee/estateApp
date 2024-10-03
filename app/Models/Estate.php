<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Estate extends Model
{
    use HasFactory;
    protected $primaryKey = 'e_id';


    public function getState(){
        return $this->belongsTo(State::class, 'e_state_id');
    }

    public function getCountry(){
        return $this->belongsTo(Country::class, 'e_country_id');
    }

    public function getProperties(){
        return $this->hasMany(Property::class, 'estate_id');
    }

    public function getAddedBy(){
        return $this->belongsTo(User::class, 'e_added_by');
    }

    public function getEstateAccount(){
        return $this->belongsTo(ChartOfAccount::class, 'id');
    }

    public function updateEstateAccountSettings(Request $request){
        $app =  Estate::find($request->estate);
        $app->property_account = $request->property_account;
        $app->customer_account = $request->customer_account;
        $app->vendor_account = $request->vendor_account;
        $app->tax_account = $request->tax_account;
        $app->refund_account = $request->refund_account;
        $app->charges_account = $request->charges_account;
        $app->salary_account = $request->salary_account;
        $app->employee_account = $request->employee_account;
        $app->workflow_account = $request->workflow_account;
        $app->general_account = $request->general_account;
        $app->save();
    }

    public function addNewEstate(Request $request){
        $record = new Estate();
        $record->e_name = $request->name ?? '';
        $record->e_country_id = $request->country ?? '';
        $record->e_state_id = $request->state ?? '';
        $record->e_city = $request->city ?? '';
        $record->e_mobile_no = $request->mobile_no ?? '';
        $record->e_address = $request->address ?? '';
        $record->e_slug = substr(sha1(time()),29,40);
        $record->e_info = $request->info ?? '';
        $record->e_ref_code = strtoupper($request->referenceCode) ;
        $record->e_added_by = Auth::user()->id;
        $record->save();
        return $record;
    }
    public function updateEstate(Request $request){
        $record =  Estate::find($request->estate);
        $record->e_name = $request->name ?? '';
        $record->e_state_id = $request->state ?? '';
        $record->e_country_id = $request->country ?? '';
        $record->e_city = $request->city ?? '';
        $record->e_mobile_no = $request->mobile_no ?? '';
        $record->e_address = $request->address ?? '';
        $record->e_slug = substr(sha1(time()),29,40);
        $record->e_info = $request->info ?? '';
        $record->e_ref_code = strtoupper($request->referenceCode) ;
        //$record->e_added_by = Auth::user()->id;
        $record->save();
        return $record;
    }


    public function getAllEstates(){
        return Estate::orderBy('e_name', 'ASC')->get();
    }

    public function getEstateBySlug($slug){
        return Estate::where('e_slug',$slug)->first();
    }
    public static function getEstateByName($name){
        return Estate::where('e_name',$name)->first();
    }

    public static function getEstateById($id){
        return Estate::find($id);
    }
    public static function getEstateByRefCode($code){
        return Estate::where('e_ref_code', $code)->first();
    }

    public function getChartOfAccountById($id){
        return ChartOfAccount::find($id);
    }

}
