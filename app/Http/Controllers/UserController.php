<?php

namespace App\Http\Controllers;

use App\Mail\NewUserMail;
use App\Models\ActivityLog;
use App\Models\ChurchBranch;
use App\Models\Country;
use App\Models\EmailQueue;
use App\Models\MaritalStatus;
use App\Models\ModuleManager;
use App\Models\Post;
use App\Models\PostCorrespondingPerson;
use App\Models\Role;
use App\Models\Wallpaper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role as SRole;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        $this->user = new User();
        $this->country = new Country();
        $this->churchbranch = new ChurchBranch();
        $this->maritalstatus = new MaritalStatus();
        $this->modulemanager = new ModuleManager();
        $this->role = new Role();

        $this->post = new Post();
        $this->postcorrespondingpersons = new PostCorrespondingPerson();
        $this->wallpaper = new Wallpaper();
    }

    public function customerDashboard(){
        return 'Customer';
    }

    public function reGenerateApiToken(){
        $this->user->apiTokenGenerator();
        session()->flash("success", "New API token re-generated.");
        return back();
    }

    public function showPractitioners(){
        return view('administration.practitioners.index',[
            'users'=>$this->user->getAllOrganizationUsers()
        ]);
    }

    public function showAdministrators(){
        return view('administration.administrators',[
            'users'=>$this->user->getAllOrganizationUsers()
        ]);
    }

/*
    public function showTimeline(){
        $branchId = Auth::user()->branch ?? 1;
        if(empty($branchId)){
            abort(404);
        }
        $branchPostIds = $this->postcorrespondingpersons->getCorrespondingPostsByBranch(2,$branchId)
            ->pluck('pcp_post_id')->toArray(); //branch related
        $individualPosts = $this->postcorrespondingpersons->getCorrespondingPostsByBranch(4,Auth::user()->id)
            ->pluck('pcp_post_id')->toArray(); //user related
        $regionalPosts = $this->postcorrespondingpersons->getCorrespondingPostsByBranch(3,Auth::user()->id)
            ->pluck('pcp_post_id')->toArray(); //regional
        $postIds = array_merge($branchPostIds, $individualPosts, $regionalPosts);

        return view('timeline.index',[
            'branches'=>$this->churchbranch->getAllChurchBranches(),
            'regions'=>$this->region->getRegions(),
            'users'=>$this->user->getAllBranchUsers($branchId),
            'posts'=>$this->post->getPostsByIds($postIds),
        ]);
    }*/

    public function showUserProfile($slug){
        $user = $this->user->getUserBySlug($slug);
        $branchId = $user->branch ?? 1;
        if(empty($branchId)){
            abort(404);
        }
        if(!empty($user)){
            //branch related
            $branchPostIds = $this->postcorrespondingpersons->getCorrespondingPosts(2,$branchId)
                ->pluck('pcp_post_id')->toArray();
            //user related
            $individualPosts = $this->postcorrespondingpersons->getCorrespondingPosts(4,$user->id)
                ->pluck('pcp_post_id')->toArray();
            //regional
            $regionId = 1;
            $regionalPosts = $this->postcorrespondingpersons->getCorrespondingPosts(3,$regionId)
                ->pluck('pcp_post_id')->toArray();
            //everyone
            $everyonePosts = $this->postcorrespondingpersons->getCorrespondingPosts(1,$user->id)
                ->pluck('pcp_post_id')->toArray();

            $postIds = array_merge($branchPostIds, $individualPosts, $regionalPosts, $everyonePosts);
            if($user->id != Auth::user()->id){
                $log = "You viewed ".$user->first_name." ".$user->last_name."'s profile";
                ActivityLog::registerActivity(Auth::user()->org_id, null, Auth::user()->id, null, 'Profile View', $log);
                //secondary person
                $log = Auth::user()->first_name." ".Auth::user()->last_name." viewed your profile";
                ActivityLog::registerActivity(Auth::user()->org_id, null, $user->id, null, 'Profile View', $log);
            }
            //if($user->type == 1){ //admin
                return view('company.persons.profile',[
                //return view('administration.profile',[
                    'user'=>$user,
                    'modules'=>$this->modulemanager->getModules(),
                    'roles'=>$this->role->getRoles(),
                   // 'posts'=>$this->post->getPostsByIds($postIds),
                    'countries'=>$this->country->getCountries(),
                    'branches'=>$this->churchbranch->getAllChurchBranches(),
                    'maritalstatus'=>$this->maritalstatus->getMaritalStatuses(),
                    'wallpapers'=>$this->wallpaper->getAllWallpapers()
                ]);
            /*else{
                return view('company.persons.profile',[
                    'user'=>$user,
                    //'modules'=>$this->modulemanager->getModules(),
                    //'roles'=>$this->role->getRoles(),
                    //'posts'=>$this->post->getPostsByIds($postIds),
                    'countries'=>$this->country->getCountries(),
                    //'branches'=>$this->churchbranch->getAllChurchBranches(),
                    'maritalstatus'=>$this->maritalstatus->getMaritalStatuses(),
                ]);
            }*/


        }else{
            session()->flash("error", 'Whoops! No record found.');
            return back();
        }
    }

    public function addNewUser(Request $request){
        $this->validate($request,[
            "firstName"=>"required",
            "lastName"=>"required",
            "email"=>"required|email|unique:users,email",
            "userType"=>'required',
            "mobileNo"=>'required',
            "dob"=>'required|date',
            "occupation"=>'required',
            "nationality"=>'required',
            "maritalStatus"=>'required',
            "presentAddress"=>'required',
            "branch"=>'required',
            "role"=>'required',
        ],[
            "firstName.required"=>"What's the person's first name?",
            "lastName.required"=>"Last name is very much important. What's the person's last name?",
            "email.required"=>"Enter a valid email address",
            "email.email"=>"Enter a valid email address",
            "email.unique"=>"There's already an account with this email address. Try another one.",
            "mobileNo.required"=>"Enter mobile number.",
            "dob.required"=>"Enter date of birth",
            "dob.date"=>"Invalid date format",
            "occupation.required"=>"What does this person do for a living?",
            "nationality.required"=>"What's the person's country or nationality?",
            "maritalStatus.required"=>"Choose marital status",
            "presentAddress.required"=>"Enter current or residential address",
            "branch.required"=>"Assign this person to a branch",
            "role.required"=>"What role best fits this person?",
        ]);
        try {
            $password = Str::random(8);
            $user = $this->user->createUser($request, $password);
            $role = $this->role->getRoleById($request->role);
            if(!empty($role)){
                $user->assignRole($role->name);
            }

            if(isset($request->avatar)){
                $this->user->uploadProfilePicture($request->avatar, $user->id);
            }

            $log = Auth::user()->first_name." ".Auth::user()->last_name." created $user->first_name $user->last_name 's account";
            ActivityLog::registerActivity(Auth::user()->org_id, null, Auth::user()->id, null, 'Account Creation', $log);
            $message = "You've successfully added a new  user to the system.";
            //try {
                Mail::to($user)->send(new NewUserMail($user, $password));
                EmailQueue::queueEmail($user->id, 'New Account', 'Your account was successfully registered.');
                //\Mail::to($contact)->send(new ReceiptMailer($receipt, $contact));
           /* } catch (\Exception $exception) {

            }*/

            session()->flash("success", $message);
            return back();
        }catch (\Exception $exception){
            session()->flash("error", 'Whoops! Something went wrong. Try again later.');
            return back();
        }

    }
    public function updateUserProfile(Request $request){
        $this->validate($request,[
            "firstName"=>"required",
            "lastName"=>"required",
            "mobileNo"=>'required',
            "occupation"=>'required',
            "nationality"=>'required',
            "maritalStatus"=>'required',
            "presentAddress"=>'required',
            //"branch"=>'required',
            "userId"=>'required',
        ],[
            "firstName.required"=>"What's the person's first name?",
            "lastName.required"=>"Last name is very much important. What's the person's last name?",
            "mobileNo.required"=>"Enter mobile number.",
            "occupation.required"=>"What does this person do for a living?",
            "nationality.required"=>"What's the person's country or nationality?",
            "maritalStatus.required"=>"Choose marital status",
            "presentAddress.required"=>"Enter current or residential address",
        ]);
        try {
             $user = $this->user->updateUserAccount($request);
            if(isset($request->avatar)){
                $this->user->uploadProfilePicture($request->avatar, $user->id);
            }
            $log = Auth::user()->first_name." ".Auth::user()->last_name." updated $user->first_name $user->last_name 's profile";
            ActivityLog::registerActivity(Auth::user()->org_id, null, Auth::user()->id, null, 'Account update', $log);
            //secondary
            $log = Auth::user()->first_name." ".Auth::user()->last_name." your profile";
            ActivityLog::registerActivity(Auth::user()->org_id, null, $user->id, null, 'Account update', $log);
            $message = "Action successful!";
            session()->flash("success", $message);
            return back();
        }catch (\Exception $exception){
            session()->flash("error", 'Whoops! Something went wrong. Try again later.');
            return back();
        }

    }

    public function assignRevokeRole(Request $request){
        $this->validate($request, [
            'role'=>'required',
            'userId'=>'required',
            //'action'=>'required', //1 for assignment, 2= for revoke
        ],[
            'role.required'=>'Choose role to assign',
            'userId.required'=>''
        ]);
        $role = SRole::findById($request->role, 'web');
        $user = $this->user->getUserById($request->userId);
        if(!empty($role) && !empty($user)){
            $user->syncRoles([$role->name]);
            session()->flash("success", "Action successful!");
            return back();
        }else{
            session()->flash("error", "Whoops! Something went wrong. Try again later.");
            return back();
        }

    }


    public function deleteUser(Request $request){
        $this->validate($request,[
            "userId"=>"required",
            "status"=>"required"
        ]);
        $user = $this->user->getUserById($request->userId);
        if(!empty($user)){
            $status = $request->status == 1 ? 'activated' : 'deactivated';
            $authUser = Auth::user();
            $log = "$authUser->first_name($authUser->email) $status this account";
            ActivityLog::registerActivity(Auth::user()->org_id, null, $user->id, null, "Account $status", $log);
            $this->user->archiveUser($request->userId, $request->status);
            session()->flash("success", "Account $status!");
            //session()->flash("success", "This function is disabled for a testing purpose.");
            return back();
        }else{
            return back();
        }
    }

    public function grantPermission(Request $request){
        $this->validate($request,[
            'permission'=>'required|array',
            'permission.*'=>'required',
            'user'=>'required'
        ]);
        $user = $this->user->getUserById($request->user);
        if(!empty($user)){
            $user->syncPermissions($request->permission);
            session()->flash("success", "You've successfully granted permission(s) to $user->first_name");
            return back();
        }else{
            session()->flash("error", "Whoops! Something went wrong. Please try again.");
            return back();
        }

    }


    public function showAddNewPastorForm(){
        return view('administration.add-new-user',[
            'countries'=>$this->country->getCountries(),
            'branches'=>$this->churchbranch->getAllChurchBranches(),
            'maritalstatus'=>$this->maritalstatus->getMaritalStatuses(),
            'roles'=>$this->role->getRoles()
        ]);
    }
    public function switchWallpaper(Request $request){
        $this->validate($request,[
            "wallpaper"=>"required"
        ],[
            "wallpaper.required"=>"Choose wallpaper"
        ]);
        $user = User::find(Auth::user()->id);
        $user->wallpaper = $request->wallpaper;
        $user->save();
        if($user){
            return response()->json(['message'=>'Success! Wallpaper changed.'], 200);
        }else{
            return response()->json(['error'=>'Whoops! Could not change wallpaper.'], 400);

        }

    }
}
