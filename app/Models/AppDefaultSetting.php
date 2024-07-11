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
            $existing->new_app_section_handler = $request->new_app_section;
            $existing->licence_renewal_handler = $request->licence_renewal;
            $existing->engage_customer = $request->engage_customer;
            $existing->frequency_assignment_handler = $request->frequency_assignment_handler;
            $existing->save();
        }else{
            $app = new AppDefaultSetting();
            $app->new_app_section_handler = $request->new_app_section;
            $app->licence_renewal_handler = $request->licence_renewal;
            $app->engage_customer = $request->engage_customer;
            $app->frequency_assignment_handler = $request->frequency_assignment_handler;
            $app->save();
        }
    }
}
