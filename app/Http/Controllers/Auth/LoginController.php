<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->user = new User();
    }

    public function login(Request $request){
        $this->validate($request,[
            'email'=>'required|email',
            'password'=>'required'
        ],[
            'email.required'=>'Enter your registered email address',
            'email.email'=>'Enter a valid email address',
            'password.required'=>'Enter your password for this account'
        ]);
        $user = $this->user->getUserByEmail($request->email);
        if(!empty($user)){
            if(Auth::attempt(['email'=>$request->email, 'password'=>$request->password], $request->remember)){
                //return dd($user);
                if($user->status == 2){
                    $log = $user->first_name." ".$user->last_name." tried logging in.";
                    ActivityLog::registerActivity($user->org_id, null, $user->id, null, 'Login attempt', $log);
                    Auth::logout();
                    session()->flash("error", " Your account is no longer active. Kindly contact admin.");
                    return back();
                }
                if(($user->getUserRole->name == 'Tech Support') || ($user->getUserRole->name == 'Super Admin')){ //admin
                    $log = $user->first_name." ".$user->last_name." logged in successfully.";
                    ActivityLog::registerActivity($user->org_id, null, $user->id, null, 'New login', $log);
                    return redirect()->route('marketing-dashboard');
                }else{
                    $log = $user->first_name." ".$user->last_name." logged in successfully.";
                    ActivityLog::registerActivity($user->org_id, null, $user->id, null, 'New login', $log);
                    return redirect()->route('user-profile', $user->slug);
                }

            }else{
                session()->flash("error", " Wrong or invalid login credentials. Try again.");
                return back();
            }
        }else{
            session()->flash("error", "There's no existing account with this login details. Try again.");
            return back();
        }
    }

}
