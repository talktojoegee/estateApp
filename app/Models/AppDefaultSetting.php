<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AppDefaultSetting extends Model
{
    use HasFactory;


    public function getDepartmentById($id){
        return ChurchBranch::find($id);
    }

    public function getAppDefaultSettings(){
        return AppDefaultSetting::first();
    }



    public function addAppDefaultSetting(Request $request){
        $existing = $this->getAppDefaultSettings();
        if(!empty($existing)){
            $existing->property_account = $request->property_account;
            $existing->customer_account = $request->customer_account;
            $existing->vendor_account = $request->vendor_account;
            $existing->tax_account = $request->tax_account;
            $existing->refund_account = $request->refund_account;
            $existing->charges_account = $request->charges_account;
            $existing->salary_account = $request->salary_account;
            $existing->employee_account = $request->employee_account;
            $existing->workflow_account = $request->workflow_account;
            $existing->general_account = $request->general_account;
            $existing->save();
        }else{
            $app = new AppDefaultSetting();
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
    }


    public function getAccountByGLCode($glCode){
        return ChartOfAccount::where('glcode',$glCode)->first();
    }
}
