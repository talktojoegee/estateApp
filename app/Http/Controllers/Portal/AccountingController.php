<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Coa;
use App\Models\TransactionCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->coa = new Coa();
        $this->transactioncategory = new TransactionCategory();
    }


    public function showChartOfAccounts(){
        return view('accounting.chart-of-accounts',[
            'accounts'=>$this->coa->getAllChartOfAccounts()
        ]);
    }


    public function showCreateChartOfAccountForm(){
        return view('accounting.create-account');
    }

    public function getParentAccount(Request $request){
        $request->validate([
            'account_type'=>'required',
            'type'=>'required'
        ]);
        $accounts = $this->coa->getChartOfAccountByAccountType($request->type, $request->account_type);
        return view('accounting.partials._accounts', ['accounts'=>$accounts]);
    }

    public function saveAccount(Request $request){
        $request->validate([
            "glcode"=>"required|unique:coas,glcode",
            "account_type"=>"required",
            "type"=>"required",
            "bank"=>"required",
            "parent_account"=>"required"
        ],[
            'glcode.required'=>'Enter GL code',
            'glcode.uniqure'=>'An account already exist with that GL code',
            'account_type.required'=>'Choose an account type',
            'type.required'=>'Indicate whether the account is detail or general account type',
            'bank.required'=>'Is this account associated to your bank?',
            'parent_account.required'=>'Choose main account',

        ]);
        try{
            $this->coa->addAccount($request);
            session()->flash("success", "Your account was created.");
            return back();
        }catch (\Exception $exception){
            session()->flash("error", "Whoops! Something went wrong.");
            return back();
        }

    }

    public function showJournalVoucherForm(){
        return view('accounting.journal-voucher',[
            'accounts'=>$this->coa->getAllChartOfAccountsByType(1)
        ]);
    }


    public function showManageCategories(){
        return view('accounting.categories',[
            'categories'=>$this->transactioncategory->getBranchCategories(Auth::user()->branch),
        ]);
    }

    public function addTransactionCategory(Request $request){
        $request->validate([
            "type"=>"required"
        ]);
        if($request->type == 1){
            $request->validate([
                "name"=>"required",
                "type"=>"required",
                "status"=>"required",
                "remittance"=>"required",
                "remittance_rate"=>"required",
            ],[
                'name.required'=>'Enter a name for this category',
                'type.required'=>'What will this account be used to track? Income or expense?',
                'status.required'=>'Active or inactive?',
                'remittance.required'=>'Kindly indicate if remittance should be paid or not',
                'remittance_rate.required'=>'What should be the remittance rate? Ignore if remittance is not required',
            ]);
        }else{
            $request->validate([
                "name"=>"required",
                "type"=>"required",
                "status"=>"required",
                //"remittance"=>"required",
                //"remittance_rate"=>"required",
            ],[
                'name.required'=>'Enter a name for this category',
                'type.required'=>'What will this account be used to track? Income or expense?',
                'status.required'=>'Active or inactive?',
                'remittance.required'=>'Kindly indicate if remittance should be paid or not',
                'remittance_rate.required'=>'What should be the remittance rate? Ignore if remittance is not required',
            ]);
        }

        try{
            $validation = $this->transactioncategory
                ->checkNameExistForBranch($request->name, Auth::user()->branch, $request->type);
            if(!empty($validation)){
                session()->flash("error", "Whoops! There's already category with the name you entered.");
                return back();
            }
            if($request->type == 1){
                $this->transactioncategory->addTransactionCategory($request->name, $request->type, $request->remittance, $request->remittance_rate);
            }else{
                $this->transactioncategory->addTransactionCategory($request->name, $request->type, 0, 0);
            }

            session()->flash("success", "Action successful!");
            return back();
        }catch (\Exception $exception){
            session()->flash("error", "Whoops! Something went wrong.");
            return back();
        }

    }
    public function editTransactionCategory(Request $request){
        $request->validate([
            "name"=>"required",
            "type"=>"required",
            "status"=>"required",
            "catId"=>'required'

        ],[
            'name.required'=>'Enter a name for this category',
            'type.required'=>'What will this account be used to track? Income or expense?',
            'status.required'=>'Active or inactive?',
        ]);
        try{
            $validation = $this->transactioncategory
                ->checkNameExistForBranch($request->name, Auth::user()->branch, $request->type);
            if(!empty($validation)){
                if(($validation->tc_status != $request->status) || ($validation->tc_type != $request->type) || ($validation->tc_remittable != $request->remittance) || ($validation->tc_proposed_rate != $request->remittance_rate) ){
                    $this->transactioncategory->editTransactionCategory($request->catId, $request->name,
                        $request->type, $request->status,$request->remittance, $request->remittance_rate);
                    session()->flash("success", "Action successful. Your changes were saved!");
                    return back();
                }else{
                    session()->flash("error", "Whoops! There's already category with the name you entered.");
                    return back();
                }
            }
            $this->transactioncategory->editTransactionCategory($request->catId, $request->name,
                $request->type, $request->status,$request->remittance, $request->remittance_rate);
            session()->flash("success", "Action successful. Your changes were saved!");
            return back();
        }catch (\Exception $exception){
            session()->flash("error", "Whoops! Something went wrong.");
            return back();
        }

    }


}
