<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Organization;
use App\Models\OrganizationDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->company = new Organization();
        $this->companydoc = new OrganizationDocument();
        $this->log = new ActivityLog();
    }

    public function showCompanyProfile(){
        $authUser = Auth::user();
        return view('company.index',[
            'company'=>$this->company->getUserOrganization($authUser->org_id),
            'logs'=>$this->log->getAllCompanyActivityLog($authUser->org_id),
            'files'=>$this->companydoc->getCompanyDocuments($authUser->org_id),
        ]);
    }

    public function uploadDocument(Request $request){
        $this->validate($request,[
            'attachment'=>'required',
            'type'=>'required',
        ],[
            "attachment.required"=>"Choose file to upload",
            "type.required"=>''
        ]);
        $this->companydoc->uploadFile($request);
        $authUser = Auth::user();
        $type = $request->type == 1 ? 'CAC certificate' : 'Current TAX Clearance Certificate';
        $log = "$authUser->first_name $authUser->last_name ($authUser->email) uploaded $type";
        ActivityLog::registerActivity($authUser->org_id, null, $authUser->id, null, 'New Document uploaded', $log);
        session()->flash("success", "Action successful. Our team will review your file.");
        return back();
    }
    public function updateCompanyProfile(Request $request){
        $this->validate($request,[
            'companyName'=>'required',
            'rcNo'=>'required',
            'mobileNo'=>'required',
            'yoi'=>'required',
            'presentAddress'=>'required',
        ],[
            "companyName.required"=>"Your company name is required",
            "rcNo.required"=>"Enter your company RC. No",
            "mobileNo.required"=>'Enter a functional mobile number',
            "yoi.required"=>'Enter year of incorporation',
            "presentAddress.required"=>'Enter your current office address',
        ]);
        $authUser = Auth::user();
        $company = $this->company->getUserOrganization($authUser->org_id);
        if(empty($company)){
            session()->flash("error", "Whoops! No records found.");
            return back();
        }
        $this->company->updateOrganizationSettings($request);
        if(isset($request->logo)){
            $this->company->uploadCompanyLogo($request->logo, $authUser->org_id);
        }

        $log = "$authUser->first_name $authUser->last_name ($authUser->email) updated the company profile.";
        ActivityLog::registerActivity($authUser->org_id, null, $authUser->id, null, 'Company profile update', $log);
        session()->flash("success", "Action successful.");
        return back();
    }
}