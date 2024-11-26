<?php

namespace App\Http\Controllers\portal;

use App\Http\Controllers\Controller;
use App\Http\Traits\OkraOpenBankingTrait;
use App\Imports\BulkCashbookImportDetailImport;
use App\Imports\BulkImportLead;
use App\Models\ActivityLog;
use App\Models\Attendance;
use App\Models\Automation;
use App\Models\BulkCashbookImportDetail;
use App\Models\BulkCashbookImportMaster;
use App\Models\CashBook;
use App\Models\CashBookAccount;
use App\Models\CashBookAttachment;
use App\Models\Client;
use App\Models\Currency;
use App\Models\FolderModel;
use App\Models\InvoiceMaster;
use App\Models\Lead;
use App\Models\LeadBulkImportDetail;
use App\Models\LeadBulkImportMaster;
use App\Models\LeadFollowupScheduleDetail;
use App\Models\LeadFollowupScheduleMaster;
use App\Models\LeadNote;
use App\Models\LeadPartner;
use App\Models\LeadSource;
use App\Models\LeadStatus;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Property;
use App\Models\Receipt;
use App\Models\Remittance;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\TransactionCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Excel;
use Illuminate\Support\Str;

class SalesnMarketingController extends Controller
{
    use OkraOpenBankingTrait;
    protected $from, $to;

    public function __construct(){
        $this->middleware('auth');
        $this->productcategory = new ProductCategory();
        $this->product = new Product();
        $this->client = new Client();
        $this->sale = new Sale();
        $this->saleitem = new SaleItem();
        $this->lead = new Lead();
        $this->leadsource = new LeadSource();
        $this->leadstatus = new LeadStatus();
        $this->leadnote = new LeadNote();
        $this->message = new Message();
        $this->automation = new Automation();

        $this->cashbookaccount = new CashBookAccount();
        $this->transactiontype = new TransactionCategory();
        $this->cashbook = new CashBook();
        $this->currency = new Currency();
        $this->casbookattachment = new CashBookAttachment();
        $this->remittance = new Remittance();
        $this->bulkcashbookimportmaster = new BulkCashbookImportMaster();
        $this->bulkcashbookimportdetails = new BulkCashbookImportDetail();

        $this->leadbulkimportmaster = new LeadBulkImportMaster();
        $this->leadbulkimportdetail = new LeadBulkImportDetail();
        $this->leadfollowupmaster = new LeadFollowupScheduleMaster();
        $this->leadfollowupdetail = new LeadFollowupScheduleDetail();
        $this->attendance = new Attendance();

        $this->invicemaster = new InvoiceMaster();
        $this->receipt = new Receipt();
        $this->property = new Property();
        $this->user = new User();
        //$this->folder = new FolderModel();
    }

    public function showAllProducts()
    {
        return view('products.index',[
            'categories'=>$this->productcategory->getAllOrgProductCategories(),
            'products'=>$this->product->getAllOrgProducts()
        ]);
    }

    public function addProductCategory(Request $request){
        $this->validate($request,[
            'name'=>'required'
        ],[
            "name.required"=>"What's the name for this product?"
        ]);
        $this->productcategory->addProductCategory($request);
        session()->flash("success", "Your product category saved!");
        return back();
    }
    public function editProductCategory(Request $request){
        $this->validate($request,[
            'name'=>'required',
            'categoryId'=>'required',
        ],[
            "name.required"=>"What's the name for this product?"
        ]);
        $this->productcategory->editProductCategory($request);
        session()->flash("success", "Your changes were saved!");
        return back();
    }

    public function addProduct(Request $request){
        $this->validate($request,[
            'productName'=>'required',
            'productCategory'=>'required',
            'cost'=>'required',
            'price'=>'required',
            'photo'=>'required|image|mimes:jpeg,png,gif|max:2048',
        ],[
            "productName.required"=>"Enter product name",
            "productCategory.required"=>"Select product category",
            "cost.required"=>"What's the cost of this product?",
            "price.required"=>"How much do you intend selling this product?",
            "photo.required"=>"Upload a product photo",
            "photo.mimes"=>"Unsupported file format.",
            "photo.max"=>"File size exceeds 2MB",
        ]);
        $this->product->addProduct($request);
        session()->flash("success", "Your product was added!");
        return back();
    }
    public function editProduct(Request $request){
        $this->validate($request,[
            'productName'=>'required',
            'productCategory'=>'required',
            'cost'=>'required',
            'price'=>'required',
            'productId'=>'required',
        ],[
            "productName.required"=>"Enter product name",
            "productCategory.required"=>"Select product category",
            "cost.required"=>"What's the cost of this product?",
            "price.required"=>"How much do you intend selling this product?",
        ]);
        $this->product->editProduct($request);
        session()->flash("success", "Your changes were saved!");
        return back();
    }

    public function showIncome(){
        //$r = $this->okraAuth();
        //return dd($r);
        $branchId = Auth::user()->branch;
        return view('income.index',[
            'accounts'=>$this->cashbookaccount->getBranchAccounts($branchId),
            'categories'=>$this->transactiontype->getBranchCategoriesByType($branchId, 1),
            'currencies'=>$this->currency->getCurrencies(),
            //'products'=>$this->product->getAllOrgProducts(),
            'income'=>$this->cashbook->getAllBranchLocalTransactions(1),
            'fxIncomes'=>$this->cashbook->getAllBranchFxTransactions(1),
            'defaultCurrency'=>$this->cashbook->getDefaultCurrency(),
            'lastMonth'=>$this->cashbook->getBranchLastMonths($branchId),
            'thisMonth'=>$this->cashbook->getBranchMonths($branchId),
            'thisWeek'=>$this->cashbook->getBranchThisWeek($branchId),
        ]);
    }

    public function recordIncome(Request $request){
        $this->validate($request,[
            "account"=>"required",
            "date"=>"required|date",
            "paymentMethod"=>"required",
            "category"=>"required",
            "transactionType"=>"required",
            "amount"=>"required",
            "currency"=>"required",
            "narration"=>"required",
        ],[
            "account.required"=>"Choose an account for this transaction",
            "date.required"=>"Enter transaction date",
            "date.date"=>"Enter a valid date format",
            "paymentMethod.required"=>"Select payment method",
            "category.required"=>"Select category from the list provided.",
            "amount.required"=>"Enter an amount for this transaction",
            "currency.required"=>"Choose currency",
            "narration.required"=>"Leave a brief narration",
        ]);
        try{
            $branchId = Auth::user()->branch;
            $debit = $request->transactionType == 1 ? 0 : $request->amount;
            $credit = $request->transactionType == 1 ? $request->amount : 0;
            $refCode = $this->cashbook->generateReferenceCode();

           $cashbook =  $this->cashbook->addCashBook($branchId, $request->category, $request->account, $request->currency,
            $request->paymentMethod, 0, $request->transactionType, $request->date,
               $request->narration,$request->narration, $debit, $credit, $refCode,date('m', strtotime($request->date)), date('Y', strtotime($request->date)));

            if ($request->hasFile('attachments')) {
                $this->casbookattachment->storeAttachment($request, $cashbook->cashbook_id);
            }
            //setNewNotification($subject, $body, $route_name, $route_param, $route_type, $user_id, $orgId)
            //Notification::setNewNotification('New invoice generated', 'A new invoice was raised for a client.',
            //    'view-client-profile', $sales->slug, 1, Auth::user()->id, Auth::user()->org_id);
            session()->flash("success", "Action successful!");
            return back();
        }catch (\Exception $exception){
            session()->flash("error", "Whoops! Something went wrong.");
            return back();
        }

    }



    public function showExpense(){
        $branchId = Auth::user()->branch;
        return view('expense.index',[
            'accounts'=>$this->cashbookaccount->getBranchAccounts($branchId),
            'categories'=>$this->transactiontype->getBranchCategoriesByType($branchId, 2),
            'currencies'=>$this->currency->getCurrencies(),
            'income'=>$this->cashbook->getAllBranchLocalTransactions(2),
            'fxIncomes'=>$this->cashbook->getAllBranchFxTransactions(2),
            'defaultCurrency'=>$this->cashbook->getDefaultCurrency(),
            'lastMonth'=>$this->cashbook->getBranchLastMonths($branchId),
            'thisMonth'=>$this->cashbook->getBranchMonths($branchId),
            'thisWeek'=>$this->cashbook->getBranchThisWeek($branchId),
        ]);
    }

    public function recordExpense(Request $request){
        $this->validate($request,[
            "account"=>"required",
            "date"=>"required|date",
            "paymentMethod"=>"required",
            "category"=>"required",
            "transactionType"=>"required",
            "amount"=>"required",
            "currency"=>"required",
            "narration"=>"required",
        ],[
            "account.required"=>"Choose an account for this transaction",
            "date.required"=>"Enter transaction date",
            "date.date"=>"Enter a valid date format",
            "paymentMethod.required"=>"Select payment method",
            "category.required"=>"Select category from the list provided.",
            "amount.required"=>"Enter an amount for this transaction",
            "currency.required"=>"Choose currency",
            "narration.required"=>"Leave a brief narration",
        ]);
        try{
            $branchId = Auth::user()->branch;
            $debit = $request->transactionType == 1 ? 0 : $request->amount;
            $credit = $request->transactionType == 1 ? $request->amount : 0;
            $refCode = $this->cashbook->generateReferenceCode();
            $cashbook =  $this->cashbook->addCashBook($branchId, $request->category, $request->account, $request->currency,
                $request->paymentMethod, 0, $request->transactionType,
                $request->date, $request->narration,$request->narration, $debit, $credit, $refCode,date('m', strtotime($request->date)),date('Y', strtotime($request->date)));

            if ($request->hasFile('attachments')) {
                $this->casbookattachment->storeAttachment($request, $cashbook->cashbook_id);
            }
            //setNewNotification($subject, $body, $route_name, $route_param, $route_type, $user_id, $orgId)
            //Notification::setNewNotification('New invoice generated', 'A new invoice was raised for a client.',
            //    'view-client-profile', $sales->slug, 1, Auth::user()->id, Auth::user()->org_id);
            session()->flash("success", "Action successful!");
            return back();
        }catch (\Exception $exception){
            session()->flash("error", "Whoops! Something went wrong.");
            return back();
        }

    }



    public function showRemittance(){
        $from = date('Y-m-d', strtotime("-7 days"));
        $to = date('Y-m-d');
        return view('remittance.index',[
            'defaultCurrency'=>$this->cashbook->getDefaultCurrency(),
            'search'=>0,
            'from'=>$from,
            'to'=>$to,

        ]);
    }

    public function showRemittanceCollections(Request $request){
        $branchId = Auth::user()->branch;
        $this->validate($request,[
            'from'=>'required|date',
            'to'=>'required|date'
        ],[
            'from.required'=>'Choose start date',
            'from.date'=>'Enter a valid date',
            'to.date'=>'Enter a valid date',
            'to.required'=>'Choose end date',
        ]);
        $from = $request->from;
        $to = $request->to;
        //$ids = $this->cashbook->pluckCashbookIdsByDateRange($from, $to, $branchId);
        return view('remittance.index',[
            'transactions'=>$this->cashbook->getBranchMonthsUnpaidRemittance($from, $to, $branchId),
           // 'fxIncomes'=>$this->cashbook->getAllBranchFxTransactions(2),
            'defaultCurrency'=>$this->cashbook->getDefaultCurrency(),
            'localCashbookIds'=>$this->cashbook->pluckCashbookIdsByDateRange($from, $to, $branchId),
            'search'=>1,
            'from'=>$from,
            'to'=>$to,

        ]);
    }

    public function processRemittanceRequest(Request $request){
        $this->validate($request,[
            'collectionFrom'=>'required|date',
            'collectionTo'=>'required|date',
            'rate'=>'required|array',
            'rate.*'=>'required',
            'locals'=>'required|array',
            'category'=>'required|array',
            'category.*'=>'required',
            'amount'=>'required|array',
            'amount.*'=>'required',
        ],[
            'collectionFrom.required'=>'Choose start date',
            'collectionFrom.date'=>'Enter a valid date',
            'collectionTo.date'=>'Enter a valid date',
            'collectionTo.required'=>'Choose end date',
            'rate.required'=>'Enter rate',
            'category.*.required'=>'Category is required',
            'amount.*.required'=>'Amount is required'

        ]);
        $branchId = Auth::user()->branch;
        $refCode = $this->generateRefCode();
        $narration = $request->narration ?? 'No narration';
        #Push to remittance table
        for($i = 0; $i<count($request->category); $i++){
            $remittance = $this->remittance->storeRemittance($branchId, $request->amount[$i], $request->rate[$i], $request->rate[$i] > 0 ? 1 : 3, $refCode, $request->category[$i],
            1, $narration, $request->collectionFrom, $request->collectionTo );
        }
        #Update cashbook records
        $cashbookTransactions = $this->cashbook->getBulkTransactionsByCategoryIds(json_decode($request->locals[0]), $branchId);
        foreach($cashbookTransactions as $cashbookTransaction){
            $this->cashbook->updateCashbookRemittance($cashbookTransaction->cashbook_id, $refCode);
        }
        session()->flash("success", "Your transaction was successful! It is currently awaiting confirmation");
        return redirect()->route('remittance');
    }


    public function marketing(){
        $start = $request->from ?? date('Y-m-d', strtotime("-90 days"));
        $end = $request->to ?? date('Y-m-d');
        $topSelling = $this->property->getTopSellingLocationsWithin($start, $end, 10);
        $underperforming = $this->property->getUnderperformingLocationsWithin($start, $end, 10);
        $last30Days = $this->receipt->getListOfPropertiesSoldRange(date('Y-m-d', strtotime("-30 days")), $end);
        $last30Ids = $last30Days->pluck('property_id')->toArray();
        //return dd(Auth::user()->getUserRole->name);
        return view('followup.marketing',[
            'search'=>0,
            'from'=>now(),
            'to'=>now(),
            'topSelling'=>$topSelling,
            'underperforming'=>$underperforming,
            'last30Properties'=>$this->property->getPropertyList($last30Ids),
            'users'=>$this->user->getTopSellingUsers($start, $end,10)
            //'users'=>$this->user->getUserByIds($issuedByIds)
        ]);
    }

    public function showFollowupDashboardStatistics(Request $request){
        $start = $request->from ?? date('Y-m-d', strtotime("-90 days"));
        $end = $request->to ?? date('Y-m-d');
        return response()->json([
            //sales,invoice,customers||leads
            'sales'=>$this->receipt->getTotalSalesByDateRange($start, $end),
            'leads'=>$this->lead->getTotalLeadsByDateRange($start, $end),
            'invoice'=>$this->invicemaster->getTotalInvoiceByDateRange($start, $end),
        ],200);
    }


    public function filterSalesRevenueReportDashboard(Request $request){
        $this->validate($request, [
            'filterType'=>'required'
        ]);
        if($request->filterType == 2){
            $this->validate($request,[
                'from'=>'required|date',
                'to'=>'required|date'
            ],[
                'from.required'=>'Choose start period',
                'from.date'=>'Enter a valid date format',
                'to.required'=>'Choose end period',
                'to.date'=>'Enter a valid date format',
            ]);
        }
        if($request->filterType == 1){
            $from = date('Y-m-d', strtotime("-90 days"));
            $to = date('Y-m-d');
            $topSelling = $this->property->getTopSellingLocationsWithin($from, $to, 10);
            $underperforming = $this->property->getUnderperformingLocationsWithin($from, $to, 10);
            $last30Days = $this->receipt->getListOfPropertiesSoldRange(date('Y-m-d', strtotime("-30 days")), $to);
            $last30Ids = $last30Days->pluck('property_id')->toArray();
            return view('followup.marketing',[
                'topSelling'=>$topSelling,
                'underperforming'=>$underperforming,
                'last30Properties'=>$this->property->getPropertyList($last30Ids),
                'users'=>$this->user->getTopSellingUsers($from, $to,10),
                'search'=>1,
                'from'=>now(),
                'to'=>now(),
                'filterType'=>$request->filterType
            ]);
        }else if($request->filterType == 2){
            $this->from = $request->from;
            $this->to = $request->to;
            $topSelling = $this->property->getTopSellingLocationsWithin($request->from, $request->to, 10);
            $underperforming = $this->property->getUnderperformingLocationsWithin($request->from, $request->to, 10);
            $last30Days = $this->receipt->getListOfPropertiesSoldRange(date('Y-m-d', strtotime("-30 days")), $request->to);
            $last30Ids = $last30Days->pluck('property_id')->toArray();

            /*$income = $this->sale->getRangeOrgSalesReport($request->from, $request->to);
            $this->income = $this->sale->getSalesStatRange($request->from, $request->to);*/
            return view('followup.marketing',[
                'topSelling'=>$topSelling,
                'underperforming'=>$underperforming,
                'last30Properties'=>$this->property->getPropertyList($last30Ids),
                'users'=>$this->user->getTopSellingUsers($request->from, $request->to,10),
                'search'=>1,
                'from'=>$request->from,
                'to'=>$request->to,
                'filterType'=>$request->filterType
            ]);
        }
    }
    /*protected function getDateRange($from, $to){
        return array("from"=>$from, "to"=>$to);
    }*/
    public function showLeads(){
        $individual = 0; $partnership = 0; $organization = 0;
        $leads = $this->lead->getAllOrgLeads();
        foreach($leads as $lead){
            switch ($lead->customer_type){
                case 1:
                    $individual += $this->lead->getCustomerValuation($lead->id);
                    break;
                case 2:
                    $partnership += $this->lead->getCustomerValuation($lead->id);
                    break;
                case 3:
                    $organization += $this->lead->getCustomerValuation($lead->id);
                    break;
            }
        }
        return view('followup.leads',[
            'sources'=>$this->leadsource->getLeadSources(),
            'statuses'=>$this->leadstatus->getLeadStatuses(),
            'leads'=>$this->lead->getAllOrgLeads(),
            'individualValue'=>$individual,
            'partnershipValue'=>$partnership,
            'organizationValue'=>$organization,
        ]);
    }

    public function createLead(Request $request){
        $this->validate($request,[
           'customerType'=>"required"
        ],[
            "customerType.required"=>"Select customer type"
        ]);

        if($request->customerType == 1){ //individual
            $this->__validateIndividualCustomerType($request);
            $this->lead->addLead($request);
        }

        if($request->customerType == 2){ //partnership
            $this->__validatePartnershipCustomerType($request);
            $lead = null;
            foreach($request->partnerFullName as $key => $value){
                if($key == 0){ //save first contact in leads table as the main person
                    $lead = $this->lead->microAddLead($request->date, $request->customerType, $request->partnerFullName[$key], $request->partnerEmail[$key],
                        $request->partnerMobileNo[$key], $request->partnerAddress[$key], $request->partnerKinFullName[$key], $request->partnerKinMobile[$key], $request->partnerKinEmail[$key], $request->partnerKinRelationship[$key]);

                }else{
                    LeadPartner::addPartner($lead->id,$request->partnerFullName[$key], $request->partnerEmail[$key], $request->partnerMobileNo[$key], $request->partnerAddress[$key],
                        $request->partnerKinFullName[$key], $request->partnerKinMobile[$key], $request->partnerKinEmail[$key], $request->partnerKinRelationship[$key]);
                }



            }
        }
        if($request->customerType == 3){
            $this->__validateOrganizationType($request);
            $this->lead->registerOrg($request->date, $request->customerType, $request->organizationName, $request->organizationEmail,
                $request->organizationMobileNo, $request->organizationAddress, $request->resourcePersonFullName, $request->resourcePersonMobileNo, $request->resourcePersonEmail, null);
        }
        session()->flash("success", "Your lead was added to the system!");
        return back();
    }

    private function __validateIndividualCustomerType(Request $request){
        $this->validate($request,[
            'date'=>'required|date',
            'firstName'=>'required',
            'lastName'=>'required',
            'email'=>'required|email',
            'mobileNo'=>'required',
            'source'=>'required',
            'status'=>'required',
            'gender'=>'required',
            'customerType'=>'required',
        ],[
            "date.required"=>"Enter date",
            "date.date"=>"Enter a valid date",
            "firstName.required"=>"Enter client first name",
            "lastName.required"=>"Enter client last name",
            "email.required"=>"Enter client email address",
            "email.email"=>"Enter a valid email address",
            "mobileNo.required"=>"Enter client mobile phone number",
            "source.required"=>"How did this person get to hear about us? Select one of the options below",
            "status.required"=>"On what stage is this person? Kindly select...",
            "customerType.required"=>"Indicate the type of customer",
        ]);
    }
    private function __validatePartnershipCustomerType(Request $request){
        $this->validate($request,[
            'date'=>'required|date',
            'partnerFullName'=>'required|array',
            'partnerFullName.*'=>'required',

            'partnerKinFullName'=>'required|array',
            'partnerKinFullName.*'=>'required',

            'partnerEmail'=>'required|array',
            'partnerEmail.*'=>'required',

            'partnerKinMobile'=>'required|array',
            'partnerKinMobile.*'=>'required',

            'partnerMobileNo'=>'required|array',
            'partnerMobileNo.*'=>'required',

            'partnerAddress'=>'required|array',
            'partnerAddress.*'=>'required',

            'partnerKinEmail'=>'required|array',
            'partnerKinEmail.*'=>'required',

            'partnerKinRelationship'=>'required|array',
            'partnerKinRelationship.*'=>'required',
        ],
            [
            'date.required'=>'Choose entry date',
            'partnerFullName.required'=>'Enter full name',
            'partnerEmail.required'=>'Enter email address',
            'partnerMobileNo.required'=>'Enter mobile number',
            'partnerAddress.required'=>'Enter address',
        ]);
    }
    private function __validateOrganizationType(Request $request){
        $this->validate($request, [
           "organizationName"=>"required",
           "organizationEmail"=>"required",
           "organizationMobileNo"=>"required",
           "organizationAddress"=>"required",
           "resourcePersonFullName"=>"required",
           "resourcePersonMobileNo"=>"required",
           "resourcePersonEmail"=>"required",
        ]);
    }

    public function showAddNewCustomerForm(Request $request){

        $method = $request->getMethod();
        switch ($method){
            case 'GET':

            return view('followup.new-lead',[
                'sources'=>$this->leadsource->getLeadSources(),
                'statuses'=>$this->leadstatus->getLeadStatuses(),
            ]);
        }
        $this->validate($request,[
            'date'=>'required|date',
            'firstName'=>'required',
            'lastName'=>'required',
            'email'=>'required|email',
            'mobileNo'=>'required',
            'source'=>'required',
            'status'=>'required',
            'gender'=>'required',
        ],[
            "date.required"=>"Enter date",
            "date.date"=>"Enter a valid date",
            "firstName.required"=>"Enter client first name",
            "lastName.required"=>"Enter client last name",
            "email.required"=>"Enter client email address",
            "email.email"=>"Enter a valid email address",
            "mobileNo.required"=>"Enter client mobile phone number",
            "source.required"=>"How did this person get to hear about us? Select one of the options below",
            "status.required"=>"On what stage is this person? Kindly select...",
        ]);
        $this->lead->addLead($request);
        session()->flash("success", "Your lead was added to the system!");
        return back();
    }

    public function leadProfile($slug){
        $lead = $this->lead->getLeadBySlug($slug);
        if(!empty($lead)){
            return view('followup.lead-profile',[
                'client'=>$lead,
               // 'folders'=>$this->folder->getAllFolders(),
            ]);
        }else{
            return back();
        }
    }

    public function editLeadProfile(Request $request){
        $this->validate($request,[
            'firstName'=>'required',
            'lastName'=>'required',
            'email'=>'required|email',
            'mobileNo'=>'required',
            'leadId'=>'required'
        ],[
            "firstName.required"=>"Enter client first name",
            "lastName.required"=>"Enter client last name",
            "email.required"=>"Enter client email address",
            "email.email"=>"Enter a valid email address",
            "mobileNo.required"=>"Enter client mobile phone number",
        ]);
        $this->lead->editLead($request);
        if(isset($request->profilePhoto)){
            $this->lead->uploadProfilePicture($request->profilePhoto, $request->leadId);
        }
        $title = "Customer profile edited!";
        $log = Auth::user()->first_name." ".Auth::user()->last_name." edited client profile";
        ActivityLog::registerActivity(Auth::user()->org_id, $request->clientId, null, null, $title, $log);
        session()->flash("success", "Your changes were saved!");
        return back();
    }
    public function editOrganizationProfile(Request $request){
        $this->validate($request,[
            "organizationName"=>"required",
            "organizationEmail"=>"required",
            "organizationMobileNo"=>"required",
            "organizationAddress"=>"required",
            "resourcePersonFullName"=>"required",
            "resourcePersonMobileNo"=>"required",
            "resourcePersonEmail"=>"required",
            "leadId"=>"required"
        ]);
        $org = $this->lead->editOrg($request->leadId, $request->organizationName, $request->organizationEmail,
            $request->organizationMobileNo, $request->organizationAddress, $request->resourcePersonFullName, $request->resourcePersonMobileNo, $request->resourcePersonEmail, null);

        $title = "Organizational profile edited!";
        $log = Auth::user()->first_name." ".Auth::user()->last_name." edited organizational profile";
        ActivityLog::registerActivity(Auth::user()->org_id, $org->id, null, null, $title, $log);
        session()->flash("success", "Your changes were saved!");
        return back();
    }
    public function savePartnerChanges(Request $request){
        $this->validate($request,[
            'partnerFullName'=>'required',
            'partnerEmail'=>'required',
            'partnerMobileNo'=>'required',
            'partnerAddress'=>'required',
            'partnerKinFullName'=>'required',
            'partnerKinMobile'=>'required',
            'partnerKinEmail'=>'required',
            'partnerKinRelationship'=>'required',
            'partnerId'=>'required'
        ]);
        $partner = LeadPartner::find($request->partnerId);
        if(empty($partner)){
            session()->flash("error", "Whoops! Something went wrong.");
            return back();
        }
        LeadPartner::editPartner($request->partnerId, $request->partnerFullName, $request->partnerEmail,
            $request->partnerMobileNo, $request->partnerAddress,
            $request->partnerKinFullName, $request->partnerKinMobile, $request->partnerKinEmail, $request->partnerKinRelationship);
        $title = "Partner profile edited!";
        $log = Auth::user()->first_name." ".Auth::user()->last_name." edited partner profile";
        ActivityLog::registerActivity(Auth::user()->org_id, $partner->lead_id, null, null, $title, $log);
        session()->flash("success", "Your changes were saved!");
        return back();
    }

    public function leaveLeadNote(Request $request){
        $this->validate($request,[
            'addNote'=>'required',
            'leadId'=>'required',
            'rating'=>'required'
        ],[
            'addNote.required'=>'Type note in the box provided',
            'rating.required'=>'Rate this conversation'
        ]);
        $this->leadnote->addNote($request);
        $log = Auth::user()->first_name.' '.Auth::user()->last_name.' left a note';
        ActivityLog::registerActivity(Auth::user()->org_id,null,null, $request->leadId, 'New Note', $log);
        //Mark request as done {Follow-up schedule}
        if(isset($request->markAsDone)){
            $followupDetail = LeadFollowupScheduleDetail::find($request->followupDetail);
            if(!empty($followupDetail)){
                $followupDetail->status = 1; //done
                $followupDetail->save();
            }
        }
        session()->flash("success", "Your note was added to the system!");
        return back();
    }
    public function editLeadNote(Request $request){
        $this->validate($request,[
            'addNote'=>'required',
            'noteId'=>'required',
            'leadId'=>'required',
        ],[
            'addNote.required'=>'Type note in the box provided'
        ]);
        $this->leadnote->editNote($request);
        $log = Auth::user()->first_name.' '.Auth::user()->last_name.' made changes to note';
        ActivityLog::registerActivity(Auth::user()->org_id,null,null, $request->leadId, 'Note changes', $log);
        session()->flash("success", "Your changes were saved!");
        return back();
    }
    public function deleteLeadNote(Request $request){
        $this->validate($request,[
            'noteId'=>'required',
            'leadId'=>'required',
        ]);
        $this->leadnote->deleteNote($request->noteId);
        $log = Auth::user()->first_name.' '.Auth::user()->last_name.' deleted note';
        ActivityLog::registerActivity(Auth::user()->org_id,null,null, $request->leadId, 'Note deleted', $log);
        session()->flash("success", "Note deleted!");
        return back();
    }


    public function showBulkImportLeads(){
        return view('followup.leads-bulk-import');
    }

    public function processLeadBulkImport(Request $request){
        $this->validate($request, [
            'attachment'=>'required'
        ],[
            'attachment.required'=>'Choose a file to upload',
        ]);
        $file = $request->attachment;

        $this->validate($request,[
            'attachment'=>'required|max:2048',
        ],[
            'attachment.required'=>'Choose a file to upload',
            //'attachment.mimes'=>'Invalid file format. Upload either xlsx or xls file',
            'attachment.max'=>'Maximum file upload size exceeded. Your file should not exceed 2MB'
        ]);
        $bulkimport = $this->leadbulkimportmaster->publishBulkImport($request, Auth::user()->id);
        Excel::import(new BulkImportLead($request->firstRowHeader, $bulkimport->id), public_path("assets/drive/import/{$bulkimport->attachment}"));

        session()->flash("success", "Success! Bulk import done.");
        return back();
    }

    public function manageBulkLeadList(){
        return view("followup.lead-bulk-import-list",[
            "records"=>$this->leadbulkimportmaster->getAllRecords()
        ]);
    }


    public function showBulkLeadImportDetails($batchCode){
        $record = $this->leadbulkimportmaster->getRecordByBatchCode($batchCode);
        if(empty($record)){
            session()->flash("error", "Whoops! No record found");
            return back();
        }
        return view("followup.lead-bulk-import-view",[
            "record"=>$record
        ]);
    }


    public function deleteLeadRecord($recordId){
        $record = $this->leadbulkimportdetail->getLeadDetailById($recordId);
        if(empty($record)){
            session()->flash("error", "Whoops! Record does not exist");
            return back();
        }
        $record->delete();
        session()->flash("success", "Success! Record deleted");
        return back();
    }


    public function discardLeadRecord($batchCode){
        $record = $this->leadbulkimportmaster->getRecordByBatchCode($batchCode);
        if(empty($record)){
            session()->flash("error", "Whoops! Record does not exist");
            return back();
        }
        $record->status = 2;
        $record->save();
        session()->flash("success", "Success! Record discarded");
        return back();
    }

    public function postLeadRecord($batchCode){
        $record = $this->leadbulkimportmaster->getRecordByBatchCode($batchCode);
        if(empty($record)){
            session()->flash("error", "Whoops! Record does not exist");
            return back();
        }
        $record->status = 1;
        $record->actioned_by = Auth::user()->id;
        $record->action_date = now();
        $record->save();

        $items = $this->leadbulkimportdetail->getLeadDetailByMasterId($record->id);
        if(count($items) > 0){
            foreach($items as $item){
                $lead = new Lead();
                $lead->entry_date = date('Y-m-d', strtotime($item->entry_date)) ??  now();
                $lead->entry_month = date('m', strtotime($item->entry_date)) ??  now();
                $lead->entry_year = date('Y', strtotime($item->entry_date)) ??  now();
                $lead->added_by = $record->imported_by;
                $lead->org_id = 1;
                $lead->first_name = $item->first_name ?? null;
                $lead->last_name = $item->last_name ?? null;
                $lead->email = $item->email ??  null;
                $lead->phone = $item->phone ?? null;
                $lead->source_id = $item->source_id;
                $lead->gender = $item->gender;
                $lead->street = $item->address ?? null;
                $lead->slug = Str::slug($item->first_name).'-'.Str::random(8);
                $lead->save();
            }
        }
        session()->flash("success", "Success! Record posted");
        return back();
    }


    public function showScheduleFollowupForm(){
        return view('followup.new-schedule',[
            'search'=>0,
            'month'=>null,
            'year'=>null
        ]);
    }



    public function showScheduleFollowupPreview(Request $request){
        $this->validate($request,[
            'period'=>'required'
        ],[
            'period.required'=>'Choose period'
        ]);

        $month = date('m', strtotime($request->period));
        $year = date('Y', strtotime($request->period));
        $records = $this->lead->getLeadByMonthYear($month, $year);
        return view('followup.new-schedule',[
            'search'=>1,
            'records'=>$records,
            'month'=>$month,
            'year'=>$year
        ]);
    }

    public function processFollowupSchedule(Request $request){

        $this->validate($request,[
            'date'=>'required|date',
            'title'=>'required',
            'objective'=>'required',
            'leads'=>'required|array',
            'leads.*'=>'required',
            'periodMonth'=>'required',
            'periodYear'=>'required',
        ],[
            'date.required'=>'Enter a date',
            'date.date'=>'Enter a valid date',
            'title.required'=>'Enter title',
            'objective.required'=>"What's your objective?",
            'leads.required'=>"Choose at least one person from the list to schedule",
            'leads.*'=>"Choose at least one person from the list to schedule",
            'periodMonth.required'=>'',
            'periodYear.required'=>'',
        ]);
        $master =  $this->leadfollowupmaster->addScheduleMaster($request->date, $request->title,
            $request->objective, $request->periodMonth, $request->periodYear);

        foreach($request->leads as $lead){
            $this->leadfollowupdetail->addDetail($master->id, $lead);
        }
        session()->flash('success', 'Success! Follow-up scheduled.');
        return redirect()->route('schedule-follow-up');
    }

    public function manageFollowupSchedule(){
        return view('followup.manage-schedule',[
            'records'=>$this->leadfollowupmaster->getAllCurrentYearFollowupSchedules()
        ]);
    }


    public function showFollowupDetails($refCode){
        $record = $this->leadfollowupmaster->getFollowupScheduleByRefCode($refCode);
        if(empty($record)){
            abort(404);
        }
        return view('followup.manage-schedule-view',[
            'record'=>$record
        ]);
    }

    public function rateFollowupSchedule(Request $request){
        $this->validate($request,[
           'status'=>'required',
           'score'=>'required',
           'comment'=>'required',
           'schedule'=>'required',
        ],[
            "status.required"=>"Choose status to rate schedule",
            "score.required"=>"On a scale of 1 to 5, rate this schedule.",
            "comment.required"=>"Help us understand your opinion by leaving a comment.",
            "schedule.required"=>"",
        ]);
        $followup = $this->leadfollowupmaster->getLeadFollowupScheduleById($request->schedule);
        if(empty($followup)){
            session()->flash("error", 'Whoops! Something went wrong.');
            return back();
        }
        $followup->actioned_by = Auth::user()->id;
        $followup->action_date = now();
        $followup->score = $request->score ?? 1;
        $followup->comment = $request->comment ?? null;
        $followup->status = $request->status ?? 0;
        $followup->save();
        session()->flash("success", 'Action successful.');
        return back();
    }



    public function showMessaging(){
        return view('followup.messaging',[
            'messages'=>$this->message->getAllOrgMessages()
        ]);
    }
    public function showComposeMessaging(){
        return view('followup.compose-message',[
            'clients'=>$this->client->getAllOrgClients(Auth::user()->org_id),
        ]);
    }

    public function storeMessage(Request $request){
        $this->validate($request,[
            "subject"=>"required",
            "to"=>"required|array",
            "to.*"=>"required",
            "message"=>"required",
        ],[
            "subject.required"=>"What's the subject of your message?",
            "to.required"=>"Who do you intend sending this message to?",
            "message.required"=>"Let's have the content of your message",
        ]);
        $this->message->saveMessage($request);
        session()->flash("success", "Your message will be sent shortly.");
        return back();
    }


    public function showAutomations(){
        return view('followup.automations',[
            'automations'=>$this->automation->getOrgAutomations()
        ]);
    }

    public function showCreateAutomation(){
        return view('followup.create-automation');
    }
    public function showEditAutomationForm(Request $request){
        $automation = $this->automation->getAutomationBySlug($request->slug);
        if(!empty($automation)){
            return view('followup.edit-automation',[
                "automation"=>$automation
            ]);
        }else{
            return back();
        }

    }

    public function storeAutomation(Request $request){
        $this->validate($request,[
           "automationTitle"=>"required",
           "triggerAction"=>"required",
           "sendAfter"=>"required",
           "time"=>"required",
           "subject"=>"required",
           "message"=>"required",
        ],[
            "automationTitle.required"=>"What's the title of this automation?",
            "triggerAction.required"=>"What action triggers this automation?",
            "sendAfter.required"=>"Send after how many days?",
            "time.required"=>"Any time allocation?",
            "subject.required"=>"What's the subject of your message?",
            "message.required"=>"Let's get the content of your message",
        ]);
        $this->automation->addAutomation($request);
        session()->flash("success", "You've successfully created new automation");
        return back();
    }
    public function editAutomation(Request $request){
        $this->validate($request,[
           "automationTitle"=>"required",
           "triggerAction"=>"required",
           "sendAfter"=>"required",
           "time"=>"required",
           "subject"=>"required",
           "message"=>"required",
            "automateId"=>"required"
        ],[
            "automationTitle.required"=>"What's the title of this automation?",
            "triggerAction.required"=>"What action triggers this automation?",
            "sendAfter.required"=>"Send after how many days?",
            "time.required"=>"Any time allocation?",
            "subject.required"=>"What's the subject of your message?",
            "message.required"=>"Let's get the content of your message",
        ]);
        $this->automation->editAutomation($request);
        session()->flash("success", "Your changes were save!");
        return back();
    }

    private function generateRefCode()
    {
        return substr(sha1(time()),31,40);
    }

    public function showBulkImport(){
        $branchId = Auth::user()->branch;
        return view('bulk-import.index',[
            'defaultCurrency'=>$this->cashbook->getDefaultCurrency(),
            'accounts'=>$this->cashbookaccount->getBranchAccounts($branchId),
            'search'=>0,

        ]);
    }
    public function processBulkImport(Request $request){
        $this->validate($request, [
            'attachment'=>'required'
        ],[
            'attachment.required'=>'Choose a file to upload',
        ]);
        $file = $request->attachment;

        $this->validate($request,[
            'account'=>'required',
            'monthYear'=>'required',
            'batchCode'=>'required',
            'attachment'=>'required|max:2048',
        ],[
            'account.required'=>'Select an account for this operation',
            'monthYear.required'=>'Select month & year',
            'batchCode.required'=>'Enter a unique batch code',
            'attachment.required'=>'Choose a file to upload',
            //'attachment.mimes'=>'Invalid file format. Upload either xlsx or xls file',
            'attachment.max'=>'Maximum file upload size exceeded. Your file should not exceed 2MB'
        ]);
        $bulkimport = $this->bulkcashbookimportmaster->publishBulkImport($request, Auth::user()->id);

        //\Maatwebsite\Excel\Facades\Excel::
        Excel::import(new BulkCashbookImportDetailImport($request->firstRowHeader, Auth::user()->branch,Auth::user()->id,
            $bulkimport->bcim_id,$request->account,date("m", strtotime($request->monthYear)),
            date("Y", strtotime($request->monthYear)),
            $request->batchCode), public_path("assets/drive/import/{$bulkimport->bcim_attachment}"));

        session()->flash("success", "Success! Bulk import done.");
        return back();
    }


    public function approveBulkImport(){
        return view("bulk-import.approve-bulk-import",[
            "records"=>$this->bulkcashbookimportmaster->getAllBulkImport()
        ]);
    }

    public function viewBulkImport($batchCode){
        $record = $this->bulkcashbookimportmaster->getOneBulkImportByBatchCode($batchCode);
        if(empty($record)){
            session()->flash("error", "Whoops! Record does not exist");
            return back();
        }
        return view("bulk-import.view-bulk-import",[
            "record"=>$record
        ]);
    }

    public function deleteRecord($recordId){
        $record = $this->bulkcashbookimportdetails->getRecordById($recordId);
        if(empty($record)){
            session()->flash("error", "Whoops! Record does not exist");
            return back();
        }
        $record->delete();
        session()->flash("success", "Success! Record deleted");
        return back();
    }


    public function discardRecord($batchCode){
        $record = $this->bulkcashbookimportmaster->getOneBulkImportByBatchCode($batchCode);
        if(empty($record)){
            session()->flash("error", "Whoops! Record does not exist");
            return back();
        }
        $record->bcim_status = 2;
        $record->save();
        $items = $this->bulkcashbookimportdetails->getRecordByBatchCode($record->bcim_batch_code);
        if(count($items) > 0){
            foreach($items as $item){
                $item->bcid_status = 2; //discarded
                $item->save();
            }
        }
        session()->flash("success", "Success! Record discarded");
        return back();
    }

    public function postRecord($batchCode){
        $record = $this->bulkcashbookimportmaster->getOneBulkImportByBatchCode($batchCode);
        if(empty($record)){
            session()->flash("error", "Whoops! Record does not exist");
            return back();
        }
        $account = CashBookAccount::find($record->bcim_cba_id);
        if(empty($account)){
            abort(404);
        }
        $record->bcim_status = 1;
        $record->save();

        $items = $this->bulkcashbookimportdetails->getRecordByBatchCode($record->bcim_batch_code);
        if(count($items) > 0){
            foreach($items as $item){
                $item->bcid_status = 1; //posted
                $item->save();
                //push to cashbook
                $this->cashbook->addCashBook($item->bcid_branch_id, $item->bcid_category_id,
                    $item->bcid_account_id, $account->cba_currency ?? 76, 1,
                    1, $item->bcid_transaction_type, date("Y-m-d", strtotime($item->bcid_transaction_date)),
                    $item->bcid_description, $item->bcid_narration ?? null,
                    $item->bcid_debit, $item->bcid_credit, $item->bcid_ref_code,
                    $item->bcid_month, $item->bcid_year);
            }
        }
        session()->flash("success", "Success! Record posted");
        return back();
    }
}
