<?php

namespace App\Http\Controllers\Portal;

use App\Models\ActivityLog;
use App\Models\AppDefaultSetting;
use App\Models\AppSmsSetting;
use App\Models\ChartOfAccount;
use App\Models\Estate;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role as SRole;
//use Spatie\Permission\Models\Permission as SPermission;

use App\Http\Controllers\Controller;
use App\Models\AppointmentSetting;
use App\Models\AppointmentType;
use App\Models\ChurchBranch;
use App\Models\Country;
use App\Models\Location;
use App\Models\ModuleManager;
use App\Models\Organization;
use App\Models\Permission;
use App\Models\Region;
use App\Models\Role;
use App\Models\State;
use App\Models\User;
use App\Models\UserNotificationSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        $this->user = new User();
        $this->usernotificationsettings = new UserNotificationSetting();
        $this->appointmentsettings = new AppointmentSetting();
        $this->appointmenttype = new AppointmentType();
        $this->location = new Location();
        $this->country = new Country();
        $this->organization = new Organization();
        $this->churchbranch = new ChurchBranch();
        $this->region = new Region();
        $this->state = new State();
        $this->modulemanager = new ModuleManager();
        $this->permission = new Permission();
        $this->role = new Role();
        $this->appdefaultsettings = new AppDefaultSetting();
        $this->appsmsdefaultsettings = new AppSmsSetting();

        $this->chartofaccount = new ChartOfAccount();
        $this->estate = new Estate();
    }

    public function showSettingsView(){
        return view('settings.settings',[
            'countries'=>$this->country->getCountries()
        ]);
    }


    public function saveAccountSettings(Request $request){
        $this->validate($request,[
            "firstName"=>"required",
            "lastName"=>"required",
            "cellphoneNumber"=>"required",
            "email"=>"required|email",
            "country"=>"required",
            //"state"=>"required",
        ],[
            "firstName.required"=>"Enter your first name",
            "lastName.required"=>"Enter your last name",
            "cellphoneNumber.required"=>"Enter your cellphone number",
            "email.required"=>"Enter your email address",
            "email.email"=>"Enter a valid email address",
            "country.required"=>"Select your country",
            //"state.required"=>"Which state/province are you located in?",
        ]);
        $this->user->updateUserAccount($request);
        if(isset($request->profilePicture)){
            $this->user->uploadProfilePicture($request->profilePicture);
        }
        session()->flash('success', 'Your changes were save!');
        return back();
    }

    public function showNotificationSettings(){
        return view('settings.notification',[
            'notification'=>$this->user->getUserNotificationSettings(Auth::user()->id)
        ]);
    }

    public function saveNotificationSettings(Request $request){
        $this->usernotificationsettings->editDefaultUserNotificationSettings(Auth::user()->id, $request);
        session()->flash("success", "Your notification settings were updated!");
        return back();
    }

    public function showAppointmentSettings(){
        return view('settings.appointments', ['settings'=>$this->appointmentsettings->getUserAppointmentSettings()]);
    }

    public function showChangePasswordForm(){
        return view('settings.change-password');
    }

    public function changePassword(Request $request){
        $this->validate($request,[
            "currentPassword"=>"required",
            "password"=>"required|confirmed|min:6",
        ],[
            "currentPassword.required"=>"Enter your current password",
            "password.required"=>"Choose a new password",
            "password.confirmed"=>"Your re-type password does not match chosen password.",
        ]);
        $user = $this->user->getUserById(Auth::user()->id);
        if (Hash::check($request->currentPassword, $user->password)) {
            $user->password = bcrypt($request->password);
            $user->save();
            $authUser = Auth::user();
            $log = "You - $authUser->first_name $authUser->last_name ($authUser->email) recently changed your password.";
            ActivityLog::registerActivity($authUser->org_id, null, $user->id, null, 'Password Change', $log);
            session()->flash("success", "Your password was successfully changed.");
            return back();
        }else{
            session()->flash("error", "Current password does not match our record. Try again.");
            return back();
        }
    }
    public function storeDigitalSignature(Request $request){
        $this->validate($request,[
            "image"=>"required"
        ]);
        $data_uri = $request->image;
        $encoded_image = explode(",", $data_uri)[1];
        $decoded_image = base64_decode($encoded_image);
        $filename = uniqid().'.png';
        $path = 'signatures/'.$filename;
        Storage::disk('public')->put($path, $decoded_image);
        $authUser = Auth::user();
        $authUser->signature = $path;
        $authUser->save();
        return response()->json(["message"=>"Action successful"], 201);
    }

    public function updateAppointmentSettings(Request $request){
        switch ($request->type){
            case 'general-availability':
                $result = $this->appointmentsettings->editGeneralAvailability($request);
                if($result){
                    return response()->json(['message'=>"Your general availability changes were saved!"],200);
                }else{
                    return response()->json(['error'=>"Something went wrong. Try again later"],400);
                }
            case 'appointment-timing':
                $result = $this->appointmentsettings->editAppointmentTiming($request);
                if($result){
                    return response()->json(['message'=>"Your appointment timing changes were saved!"],200);
                }else{
                    return response()->json(['error'=>"Something went wrong. Try again later"]);
                }
            case 'appointment-alerts':
                $result = $this->appointmentsettings->editAppointmentAlerts($request);
                if($result){
                    return response()->json(['message'=>"Your appointment alert changes were saved!"],200);
                }else{
                    return response()->json(['error'=>"Something went wrong. Try again later"]);
                }
            case 'confirmation-cancellation':
                $result = $this->appointmentsettings->editConfirmationCancellation($request);
                if($result){
                    return response()->json(['message'=>"Your confirmation & cancellation changes were saved!"],200);
                }else{
                    return response()->json(['error'=>"Something went wrong. Try again later"]);
                }
            case 'credits':
                $result = $this->appointmentsettings->editCredits($request);
                if($result){
                    return response()->json(['message'=>"Your credits changes were saved!"],200);
                }else{
                    return response()->json(['error'=>"Something went wrong. Try again later"]);
                }
        }


    }


    public function showAppointmentTypeSettings(){
        return view('settings.appointment-types',[
            'appointmentTypes'=>$this->appointmenttype->getAppointmentTypes()
        ]);
    }

    public function storeAppointmentType(Request $request){
        $this->validate($request,[
            "name"=>"required",
            "length"=>"required",
            "allClientBook"=>"required"
        ]);
        $this->appointmenttype->addAppointmentType($request);
        session()->flash("success", "New appointment type registered!");
        return back();
    }


    public function editAppointmentType(Request $request){
        $this->validate($request,[
            "name"=>"required",
            "length"=>"required",
            "allClientBook"=>"required",
            "apptId"=>"required"
        ]);
        $this->appointmenttype->updateAppointmentType($request);
        session()->flash("success", "Your changes were saved!");
        return back();
    }

    public function showApptLocations(){
        return view('settings.appt-locations',[
            'locations'=>$this->location->getAllOrgLocations()
        ]);
    }

    public function storeApptLocations(Request $request){
        $this->validate($request,[
           "locationName"=>"required"
        ],[
            "locationName.required"=>"Enter location name"
        ]);
        $this->location->addLocation($request);
        session()->flash("success", "You've successfully added a new location");
        return back();

    }
  public function editApptLocations(Request $request){
        $this->validate($request,[
           "locationName"=>"required",
            "locationId"=>"required"
        ],[
            "locationName.required"=>"Enter location name"
        ]);
        $this->location->editLocation($request);
        session()->flash("success", "Your changes were saved!");
        return back();

    }

    public function updateOrganizationSettings(Request $request){
        $this->validate($request,[
           "subDomain"=>"required|unique:organizations,sub_domain,".Auth::user()->id,
            "organizationName"=>"required",
            "organizationPhoneNumber"=>"required",
            "organizationEmail"=>"required",
            "addressLine"=>"required",
            "city"=>"required",
            "zipCode"=>"required",
            "orgId"=>"required",
        ],[
            "organizationName.required"=>"What's the name of your organization?",
            "subDomain.required"=>"A custom sub-domain is required",
            "subDomain.unique"=>"This sub-domain is already taken.",
            "organizationPhoneNumber.required"=>"What's the organization's phone number?",
            "organizationEmail.required"=>"What's the organization's email address?",
            "addressLine.required"=>"An address is required",
            "city.required"=>"In which city are you situated?",
            "zipCode.required"=>"What's the region zip code?",
            "orgId.required"=>""
        ]);
        $this->organization->updateOrganizationSettings($request);
        if($request->hasFile('logo')){
            $this->organization->uploadLogo($request->logo);
        }
        if($request->hasFile('favicon')){
            $this->organization->uploadFavicon($request->favicon);
        }
        session()->flash("success", "Your changes were saved!");
        return back();

    }




    public function showCellsSettingsView(){
        return view('settings.settings-cells',[
            'countries'=>$this->country->getCountries()
        ]);
    }

    public function showBranchesSettingsView(){
        $countryId = env('COUNTRY_ID') ?? 1;
        return view('settings.settings-branches',[
            'countries'=>$this->country->getCountries(),
            'branches'=>$this->churchbranch->getAllChurchBranches(),
            'regions'=>$this->region->getRegions(),
            'states'=>$this->state->getStatesByCountryId($countryId)
        ]);
    }

    public function storeChurchBranch(Request $request){
        $this->validate($request,[
            "branchName"=>"required",
            //"country"=>"required",
            //"state"=>"required",
            //"address"=>"required",
            //"region"=>"required",
        ],[
            "branchName.required"=>"What will you call this branch?",
            //"country.required"=>"Which country is this branch situated?",
            //"state.required"=>"Select the state",
            //"address.required"=>"Enter address",
            //"region.required"=>"Select region",
        ]);
        try{
            $this->churchbranch->addBranch($request);
            session()->flash("success", "You've successfully added a new church branch");
            return back();
        }catch (\Exception $exception){
            session()->flash("error", "Whoops! Something went wrong. Try again later or contact admin.");
            return back();
        }


    }

    public function editChurchBranch(Request $request){
        $this->validate($request,[
            "branchName"=>"required",
            //"country"=>"required",
            //"state"=>"required",
            //"address"=>"required",
            "branchId"=>'required',
            //"region"=>'required',
        ],[
            "branchName.required"=>"What will you call this branch?",
            //"country.required"=>"Which country is this branch situated?",
            //"state.required"=>"Select the state",
            //"address.required"=>"Enter address",
            //"region.required"=>"Select region",
        ]);
        try{
            $this->churchbranch->editBranch($request);
            session()->flash("success", "Your changes were saved.");
            return back();
        }catch (\Exception $exception){
            session()->flash("error", "Whoops! Something went wrong. Try again later or contact admin.");
            return back();
        }


    }


    public function managePermissions(){
        return view('settings.settings-permissions',[
            'modules'=>$this->modulemanager->getModulesByArea(0),
            'permissions'=>$this->permission->getPermissions(),
        ]);
    }


    public function showSMSSettingsForm(){
        return view('settings.settings-sms',[
            'app_sms_setting'=>$this->appsmsdefaultsettings->getAppSmsDefaultSettings()
        ]);
    }

    public function showWorkflowSettingsForm(){
        return view('settings.settings-accounting',[
            'defaults'=>$this->appdefaultsettings->getAppDefaultSettings(),
            'accounts'=>$this->chartofaccount->getAllDetailChartOfAccounts(),
            'records'=>$this->estate->getAllEstates()
        ]);
    }

    public function appDefaultSettings(Request $request){
        $this->validate($request,[
            'property_account'=>'required',
            'customer_account'=>'required',
            'vendor_account'=>'required',
            'tax_account'=>'required',
            'refund_account'=>'required',
            'charges_account'=>'required',
            'salary_account'=>'required',
            'employee_account'=>'required',
            'workflow_account'=>'required',
            'general_account'=>'required',
        ],[
            'property_account.required'=>'Choose a default account for properties. ',
            'customer_account.required'=>'Choose a default account for customers',
            'vendor_account.required'=>'Choose a default account for vendors',
            'tax_account.required'=>'Choose a default account for tax',
            'refund_account.required'=>'Choose a default account for refund',
            'charges_account.required'=>'Choose a default account for charges',
            'salary_account.required'=>'Choose a default account for salaries',
            'workflow_account.required'=>'Choose a default account for workflow',
            'employee_account.required'=>'Choose a default account for employees',
            'general_account.required'=>'Choose a general account',
        ]);
        $authUser = Auth::user();
        $this->appdefaultsettings->addAppDefaultSetting($request);
        $log = "$authUser->first_name($authUser->email) updated accounting default settings";
        $title = "Account Default settings";
        ActivityLog::registerActivity($authUser->org_id, null, $authUser->id, null, $title, $log);
        session()->flash("success",  "Action successful.");
        return back();
    }

    public function updateEstateAccounts(Request $request){
        $this->validate($request,[
            'estate'=>'required',
            'property_account'=>'required',
            'customer_account'=>'required',
            'vendor_account'=>'required',
            'tax_account'=>'required',
            'refund_account'=>'required',
            'charges_account'=>'required',
            'salary_account'=>'required',
            'employee_account'=>'required',
            'workflow_account'=>'required',
            'general_account'=>'required',
        ],[
            'estate.required'=>'',
            'property_account.required'=>'Choose a default account for properties. ',
            'customer_account.required'=>'Choose a default account for customers',
            'vendor_account.required'=>'Choose a default account for vendors',
            'tax_account.required'=>'Choose a default account for tax',
            'refund_account.required'=>'Choose a default account for refund',
            'charges_account.required'=>'Choose a default account for charges',
            'salary_account.required'=>'Choose a default account for salaries',
            'workflow_account.required'=>'Choose a default account for workflow',
            'employee_account.required'=>'Choose a default account for employees',
            'general_account.required'=>'Choose a general account',
        ]);
        $authUser = Auth::user();
        $estate = $this->estate->getEstateById($request->estate);
        if(empty($estate)){
            session()->flash("error",  "Whoops! No record found.");
            return back();
        }
        $this->estate->updateEstateAccountSettings($request);
        $log = "$authUser->first_name($authUser->email) estate account settings";
        $title = "Estate Account settings";
        ActivityLog::registerActivity($authUser->org_id, null, $authUser->id, null, $title, $log);
        session()->flash("success",  "Action successful.");
        return back();
    }

    public function appDefaultSmsSettings(Request $request){
        $this->validate($request,[
            'new_licence_sms'=>'required',
            'licence_renewal_sms'=>'required',
            'licence_renewal_sms_ack'=>'required'
        ],[
            'new_licence_sms.required'=>'Compose an SMS for new licence acknowledgement',
            'licence_renewal_sms_ack.required'=>'Compose an SMS for licence renewal reminder',
            'licence_renewal_sms.required'=>'Compose an SMS for licence renewal acknowledgement',
        ]);
        $this->appsmsdefaultsettings->addAppSmsDefaultSetting($request);
        $authUser = Auth::user();

        $log = "$authUser->first_name($authUser->email) added new SMS message for new licence, renewal reminder";
        $title = "Default SMS settings";
        ActivityLog::registerActivity($authUser->org_id, null, $authUser->id, null, $title, $log);
        session()->flash("success",  "Your settings were saved successfully.");
        return back();
    }

    public function manageRoles(){
        return view('settings.settings-roles',[
            'roles'=>$this->role->getRoles(),
            'permissions'=>$this->permission->getPermissions(),
        ]);
    }

    public function storeRole(Request $request){
        $all = isset($request->grantAll) ? 1 : 0;
        if($all == 0){
            $this->validate($request,[
                'name'=>'required|unique:roles,name',
                'permission'=>'required|array',
                'permission.*'=>'required',
            ],[
                'name.required'=>'Enter role name',
                'name.unique'=>'Role name already taken',
                'permission.required'=>'Assign at least one permission to this role.',
            ]);
        }else{
            $this->validate($request,[
                'name'=>'required|unique:roles,name',
            ],[
                'name.required'=>'Enter role name',
                'name.unique'=>'Role name already taken',
            ]);
        }
        try {
            if($all == 0){
                $role = SRole::create([
                    'name'=>$request->name,
                    'guard_name'=>'web'
                ]);// $this->role->addRole($request);
                $role->syncPermissions($request->permission);
            }else{
                $permissions = $this->permission->getPermissions()->pluck('id');
                $role = SRole::create([
                    'name'=>$request->name,
                    'guard_name'=>'web'
                ]);// $this->role->addRole($request);
                $role->syncPermissions($permissions);
            }
            session()->flash("success", "Action successful.");
            return back();
        }catch (\Exception $exception){
            session()->flash("error", "Something went wrong. Try again later");
            return back();
        }

    }


    public function updateRolePermissions(Request $request){
        $this->validate($request,[
            'roleId'=>'required'
        ]);
        $role = SRole::findById($request->roleId);
        if(!empty($role)){
            $permissionIds = [];
            foreach($request->permission as $permit){
                array_push($permissionIds, $permit);
            }
            $permissions = $this->permission->getPermissionsByIds($permissionIds);
            //return dd($permissions);
            $role->syncPermissions($permissions);
            session()->flash("success", "Action successful.");
            return back();
        }else{
            session()->flash("error", "Something went wrong. Try again later");
            return back();
        }
    }

    public function storePermission(Request $request){
        $this->validate($request,[
            'permissionName'=>'required',
            'module'=>'required'
        ],[
            'permissionName.required'=>'Enter permission name',
            'module.required'=>'Choose the associate module',
        ]);
        $this->permission->addPermission($request);
        session()->flash("success", "Action successful");
        return back();
    }
    public function editPermission(Request $request){
        $this->validate($request,[
            'permissionName'=>'required',
            'module'=>'required',
            'permissionId'=>'required',
        ],[
            'permissionName.required'=>'Enter permission name',
            'module.required'=>'Choose the associate module',
        ]);
        $this->permission->editPermission($request);
        session()->flash("success", "Your changes were saved.");
        return back();
    }

    public function saveLogo(Request $request){
        $this->validate($request,[
            'logo' => 'required|image|max:1024|mimes:jpg,png,jpeg',
        ],[
            "logo.required"=>"Choose a logo to upload",
            "logo.image"=>"Choose an image file",
            "logo.mimes"=>"Unsupported file format chosen",
        ]);
        $this->organization->uploadLogo($request->logo);
        session()->flash("success", "Your logo was uploaded.");
        return back();
    }
    public function saveFavicon(Request $request){
        $this->validate($request, [
            'favicon' => 'required|image|max:1024|mimes:jpg,png,jpeg',
        ],[
            "favicon.required"=>"Choose a favicon to upload",
            "favicon.image"=>"Choose an image file",
            "favicon.mimes"=>"Unsupported file format chosen",
        ]);
        $this->organization->uploadFavicon($request->favicon);
        session()->flash("success", "Your favicon was uploaded.");
        return back();
    }

}
