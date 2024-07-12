<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use App\Models\Organization;
//use App\Models\CompanyPaymentIntegration;
//use App\Models\DefaultGLAccount;
use App\Models\GeneralLedger;
use App\Models\InvoiceMaster;
use App\Models\JournalVoucher;
use App\Models\Property;
use App\Models\Receipt;
use App\Models\Service;
//use App\Models\SuperAdminPaymentIntegration;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountingController extends Controller
{
    public function __construct()
    {
        $this->receipt = new Receipt();
        $this->generalledger = new GeneralLedger();
        $this->chartofaccounts = new ChartOfAccount();
        $this->invoice = new InvoiceMaster();
        //$this->tenant = new Tenant();
        //$this->tenant_app = new TenantApplicant();
        $this->property = new Property();
        $this->service = new Service();
        //$this->companypaymentintegration = new CompanyPaymentIntegration();
        //$this->superadminpaymentintegration = new SuperAdminPaymentIntegration();
        //$this->defaulaccount = new DefaultGLAccount();
        $this->journalvoucher = new JournalVoucher();
        $this->company = new Organization();
        /*$this->leasefrequency = new LeaseFrequency();
        $this->leaserenewal = new LeaseRenewal();*/
    }

    public function createMajorTransactionAccounts(){
        $this->chartofaccounts->insertDefaultAccounts();
        session()->flash("success", "Action successful");
        return back();
    }

    public function chartOfAccounts(){
        /* $key = "key_".substr(sha1(time()),21,40 );
         $invoice = $this->invoice->getInvoiceById(2);
         $current = Carbon::now();
         $propertyObj = Property::find($invoice->property_id);
         $frequencyObj = $this->leasefrequency->getLeaseFrequencyById($propertyObj->frequency);
         $tenantObj = $this->tenant->getTenantById($invoice->tenant_id);
         #Start & end date parameters
         $daysLeft = strtotime($tenantObj->end_date)  > strtotime(now())  ? $current->diffInDays($tenantObj->end_date) : 0;
         $daysPass = strtotime($tenantObj->end_date)  < strtotime(now())  ? $current->diffInDays($tenantObj->end_date) : 0;
         $end_date =  strtotime($tenantObj->end_date)  > strtotime(now())
             ? $current->parse($tenantObj->end_date)->addDays( $daysLeft + $frequencyObj->duration )
             : $current->addDays( $frequencyObj->duration - $daysPass) ;
         //return $end_date->toDateTimeString();
         $this->tenant->updateTenantLease($invoice->tenant_id, $invoice->property_id, $key, now(), $end_date->toDateTimeString() );
         #Register in lease renewal table
         $this->leaserenewal->renewTenantLease($invoice->tenant_id, $invoice->property_id, $key, now(), $end_date->toDateTimeString() );
  return "Done";*/
        $charts = $this->chartofaccounts->getCompanyChartOfAccount();
        return view('accounting.chart-of-accounts', ['charts'=>$charts]);
    }

    public function showNewChartOfAccountForm(){
        return view('accounting.new-chart-of-account');
    }

    public function addNewChartOfAccount(Request $request){
        $this->validate($request,[
            "glcode"=>"required|unique:chart_of_accounts,glcode",
            "account_name"=>"required",
            "account_type"=>"required",
            "type"=>"required",
            "bank"=>"required",
            "parent_account"=>"required"
        ],[
            'glcode.required'=>'Enter a unique account code',
            'account_name.required'=>'Enter a unique account name',
            'account_type.required'=>'What form of account is this?',
            'type.required'=>'Select whether this is a general or detail account',
            'parent_account.required'=>'Kindly select parent account',
        ]);
        $this->chartofaccounts->setNewChartOfAccount($request);
        session()->flash("success", "Action successful");
        return back();
    }

    public function getParentAccount(Request $request){
        $this->validate($request,[
            'account_type'=>'required'
        ]);
        $accounts = ChartOfAccount::select('account_name', 'id', 'type', 'glcode')
            ->where('account_type',$request->account_type)
            ->where('type',$request->type)
            ->get();
        return view('accounting.partials._accounts', ['accounts'=>$accounts]);
    }

    public function accountSettings(){
        $charts = $this->chartofaccounts->getAllChartOfAccountsByType(1);
        $defaults = $this->defaulaccount->getAllDefaultAccounts();
        $exist = 0; //0=not, 1=exist
        if(count($defaults) > 0){
            $exist = 1;
            return view('accounting.settings', ['accounts'=>$charts, 'defaults'=>$defaults, 'exist'=>$exist]);
        }else{
            $exist = 0;
            $transactions = [
                'tenant_account',
                'property_account',
                'maintenance_account',
                'vendor_account',
                'vendor_service_account',
                'receipt_account',
                'invoice_account',
                'security_fee_account',
                'tax_vat_account',
                'bank_account'
            ];
            return view('accounting.settings', ['accounts'=>$charts, 'transactions'=>$transactions, 'exist'=>$exist]);
        }
    }

    public function paymentIntegrationSetup(Request $request){
        $this->validate($request,[
            'public_key'=>'required',
            'secret_key'=>'required'
        ],[
            'public_key.required'=>'Enter your live public key',
            'secret_key.required'=>'Enter your live secret key'
        ]);
        $this->company->updateTenantPaymentIntegration($request);
        session()->flash("success", "<strong>Congratulations!</strong> Your changes were saved successfully.");
        return back();
    }
    public function showOurPricingForm(){
        //$admin_integration = $this->superadminpaymentintegration->getSuperAdminPaymentIntegrationObj();
        // if(!empty($admin_integration)){
        #Public key
        //$this->setEnv('PAYSTACK_PUBLIC_KEY', $admin_integration->ps_public_key);
        #Secret key
        //$this->setEnv('PAYSTACK_SECRET_KEY', $admin_integration->ps_secret_key);
        return view('accounting.our-pricing');
        // }else{
        //session()->flash("error", "<strong>Whoops!</strong> Something went wrong.");
        //return back();
        //}

    }

    private function setEnv($key, $value)
    {
        $current_value = env($key);
        file_put_contents(app()->environmentFilePath(), str_replace(
            $key . '='.$current_value,
            $key .'='.$value,
            file_get_contents(app()->environmentFilePath())
        ));
    }

    public function setDefaultAccounts(Request $request){

        $this->validate($request,[
            'transaction'=>'required|array',
            'transaction.*'=>'required',
            'account'=>'required|array',
            'account.*'=>'required',
        ]);
        #Truncate table
        if(count($request->transaction) != count($request->account)){
            session()->flash("error", "<strong>Whoops!</strong> Something went wrong. Ensure each transaction has a corresponding account");
            return back();
        }
        DefaultGLAccount::where('company_id', Auth::user()->company_id)->truncate();
        for($i = 0; $i<count($request->transaction); $i++){
            $default = new DefaultGLAccount;
            $default->transaction = $request->transaction[$i];
            $default->glcode = $request->account[$i];
            $default->company_id = Auth::user()->company_id;
            $default->updated_by = Auth::user()->id;
            $default->save();
        }
        session()->flash("success", "<strong>Success!</strong> Changes to default accounts saved.");
        return back();
    }


    public function manageReceipts(){
        $receipts = $this->receipt->getAllReceipts();
        return view('accounting.receipt.index', ['receipts'=>$receipts]);
    }


    public function viewReceipt($slug){
        $receipt = $this->receipt->getReceipt($slug);
        if(!empty($receipt)){
            return view('accounting.receipt.view',['receipt'=>$receipt]);
        }else{
            session()->flash("error", "<strong>Ooops!</strong> No record found.");
            return back();
        }
    }

    public function approveReceipt($slug){
        $receipt = $this->receipt->approveReceipt($slug);
        if($receipt == 1){
            session()->flash("success", "<strong>Great!</strong> Receipt approved and posted to ledger. ");
            return redirect()->route('manage-receipts');
        }else{
            session()->flash("error", "<strong>Ooops!</strong> Something went wrong. Please try again or contact admin.");
            return redirect()->route('manage-receipts');
        }
    }

    public function declineReceipt($slug){
        $receipt = $this->receipt->declineReceipt($slug);
        if($receipt == 1){
            session()->flash("success", "<strong>Great!</strong> Receipt declined. ");
            return redirect()->route('manage-receipts');
        }else{
            session()->flash("error", "<strong>Ooops!</strong> Something went wrong. Please try again or contact admin.");
            return redirect()->route('manage-receipts');
        }
    }

    /*
     * Invoice
     */
    public function manageInvoices(){
        return view('accounting.invoice.index',
            [
                'invoices'=>$this->invoice->getAllUntrashedInvoices(),
                'thisMonth'=>$this->invoice->getInvoicesForTheMonth()
            ]
        );
    }

    public function showGenerateNewInvoiceForm(){
        //$latest_invoice = $this->invoice->getLatestInvoiceNo();
        $potential_tenants = $this->tenant_app->getAllCompanyApplicants();
        $tenants = $this->tenant->getTenants();
        return view('accounting.invoice.new-invoice', [
            'potential_tenants'=>$potential_tenants,
            'tenants'=>$tenants,
            'properties'=>$this->property->getPropertiesForSale(),
            'services'=>$this->service->getAllServices()
        ]);
    }

    public function generateNewInvoice(Request $request){

        if(empty($request->invoice_type)){
            session()->flash("error", "<strong>Whoops!</strong> Kindly select invoice type");
            return back();
        }else{
            #Process invoice for new applicant
            if($request->invoice_type == 1){
                if(empty($request->applicant)){
                    session()->flash("error", "<strong>Whoops!</strong> Kindly select an applicant");
                    return back();
                }else{
                    $this->validate($request,[
                        'invoice_type'=>'required',
                        'applicant'=>'required',
                        'issue_date'=>'required|date',
                        'due_date'=>'required|date',
                        'service.*'=>'required'
                    ]);
                    $applicant = $this->tenant_app->getApplicantById($request->applicant);
                    if(!empty($applicant)){
                        $invoice_no = $this->invoice->getLatestInvoiceNo();
                        $this->invoice->generateNewInvoiceForApplicant($request, $applicant, $invoice_no);
                        session()->flash("success", "<strong>Success!</strong> New invoice generated.");
                        return back();
                    }else{
                        session()->flash("error", "<strong>Whoops!</strong> There's no record for the selected applicant.");
                        return back();
                    }
                }

            }elseif($request->invoice_type == 2){
                #Process invoice for tenant
                if(empty($request->tenant)){
                    session()->flash("error", "<strong>Whoops!</strong> Kindly select a tenant");
                    return back();
                }else{
                    //process tenant invoice
                    $this->validate($request,[
                        'invoice_type'=>'required',
                        'tenant'=>'required',
                        'issue_date'=>'required|date',
                        'due_date'=>'required|date',
                        'service.*'=>'required'
                    ]);
                    $tenant = $this->tenant->getTenantById($request->tenant);
                    if(!empty($tenant)){
                        $invoice_no = $this->invoice->getLatestInvoiceNo();
                        $this->invoice->generateNewInvoiceForTenant($request, $tenant, $invoice_no);
                        session()->flash("success", "<strong>Success!</strong> New invoice generated.");
                        return back();
                    }else{
                        session()->flash("error", "<strong>Whoops!</strong> There's no record for the selected applicant.");
                        return back();
                    }

                }
            }elseif($request->invoice_type == 3){
                #Process invoice for sale of property
                if(empty($request->tenant) || empty($request->property)){
                    session()->flash("error", "<strong>Whoops!</strong> Kindly select a tenant & property");
                    return back();
                }else{
                    //process tenant invoice
                    $this->validate($request,[
                        'invoice_type'=>'required',
                        'tenant'=>'required',
                        'property'=>'required',
                        'issue_date'=>'required|date',
                        'due_date'=>'required|date',
                        'service.*'=>'required'
                    ]);
                    $tenant = $this->tenant->getTenantById($request->tenant);
                    if(!empty($tenant)){
                        $invoice_no = $this->invoice->getLatestInvoiceNo();
                        $property = $this->property->getPropertyById($request->property);
                        $this->invoice->generateNewInvoiceForSaleOfProperty($request, $tenant, $invoice_no, $property);
                        session()->flash("success", "<strong>Success!</strong> New invoice generated.");
                        return back();
                    }else{
                        session()->flash("error", "<strong>Whoops!</strong> There's no record for the selected applicant.");
                        return back();
                    }

                }
            }elseif($request->invoice_type == 4){
                $this->validate($request,[
                    'invoice_type'=>'required',
                    'tenant'=>'required',
                    'issue_date'=>'required|date',
                    'due_date'=>'required|date',
                    'service.*'=>'required'
                ]);
                $tenant = $this->tenant->getTenantById($request->tenant);
                if(!empty($tenant)){
                    $invoice_no = $this->invoice->getLatestInvoiceNo();
                    $this->invoice->generateNewInvoiceForTenant($request, $tenant, $invoice_no);
                    session()->flash("success", "<strong>Success!</strong> New invoice generated.");
                    return back();
                }else{
                    session()->flash("error", "<strong>Whoops!</strong> There's no record for the selected applicant.");
                    return back();
                }
            }



        }
    }

    public function viewInvoice($slug){
        $invoice = $this->invoice->getInvoice($slug);
        if(!empty($invoice)){
            return view('accounting.invoice.view-invoice',['invoice'=>$invoice]);
        }else{
            session()->flash("error", "<strong>Whoops!</strong> No record found.");
            return back();
        }
    }

    public function declineInvoice($slug){
        $invoice = $this->invoice->getInvoice($slug);
        if(!empty($invoice)){
            $this->invoice->updateInvoiceStatus($invoice->id, 'decline');
            session()->flash("success", "<strong>Success!</strong> Invoice posted.");
            return back();
        }else{
            session()->flash("error", "<strong>Whoops!</strong> No record found.");
            return back();
        }
    }
    public function approveInvoice($slug){
        $invoice = $this->invoice->getInvoice($slug);
        if(!empty($invoice)){
            $status = $this->invoice->updateInvoiceStatus($invoice->id, 'post');
            /*if($status == 0){
                session()->flash("error", "<strong>Whoops!</strong> Kindly setup default accounts for transactions like this.");
                return back();
            }else{*/
            session()->flash("success", "<strong>Success!</strong> Invoice approved.");
            return back();
            //}
        }else{
            session()->flash("error", "<strong>Whoops!</strong> No record found.");
            return back();
        }
    }

    public function sendInvoiceAsEmail($slug){
        $invoice = $this->invoice->getInvoice($slug);
        if(!empty($invoice)){
            #Applicant
            if($invoice->invoice_type == 1){
                $applicant = $this->tenant_app->getApplicantById($invoice->tenant_app_id);
                if(!empty($applicant)){
                    $this->invoice->sendInvoiceAsEmailService($invoice, $applicant);
                    //return dd($applicant);
                    session()->flash("success", "<strong>Success!</strong> Invoice sent.");
                    return back();
                }else{
                    session()->flash("error", "<strong>There's no record for this applicant.");
                    return back();
                }
            }elseif($invoice->invoice_type == 2 || $invoice->invoice_type == 3){
                $tenant = $this->tenant->getTenantById($invoice->tenant_id);
                if(!empty($tenant)){
                    $tenant_app = $this->tenant_app->getApplicantById($tenant->tenant_app_id);
                    $this->invoice->sendInvoiceAsEmailService($invoice, $tenant_app);

                    session()->flash("success", "<strong>Success!</strong> Invoice sent.");
                    return back();
                }else{
                    session()->flash("error", "<strong>There's no record for this tenant.");
                    return back();
                }
            }
        }else{
            session()->flash("error", "<strong>Whoops!</strong> No record found.");
            return back();
        }
    }
    public function receivePayment($slug){
        $invoice = $this->invoice->getInvoice($slug);
        if(!empty($invoice)){
            return view('accounting.invoice.receive-payment',['invoice'=>$invoice]);
        }else{
            session()->flash("error", "<strong>Whoops!</strong> No record found.");
            return back();
        }
    }

    public function processOfflineNewReceiptPayment(Request $request){
        $this->validate($request,[
            'amount'=>'required',
            'invoice'=>'required'
        ],[
            'amount.required'=>'Enter amount paid.'
        ]);
        $invoice = $this->invoice->getInvoiceById($request->invoice);
        if(!empty($invoice)){
            $this->invoice->updateInvoicePayment($invoice, $request->amount);
            $this->receipt->generateOfflineReceipt($request, $invoice);
            session()->flash("success", "<strong>Congratulations!</strong> Your receipt request was generated successfully.");
            return back();
        }else{
            session()->flash("error", "<strong>Whoops!</strong> Invoice does not exist. Try again.");
            return back();
        }
    }
    /*
     * Accounting reports
     */
    public function showTrialBalance(){
        return view('accounting.reports.trial-balance', ['status'=>0]); //status = 0 => no accounting period entered
    }

    public function trialBalance(Request $request){
        $this->validate($request, [
            'start_date'=>'required|date',
            'end_date'=>'required|date|after_or_equal:start_date'
        ]);
        $this->current = Carbon::now();
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $firstGl = $this->generalledger->orderBy('id', 'ASC')->first();
        if(!empty($firstGl)){
            $bfDr = $this->generalledger->getDrBalanceBroughtForward($firstGl->created_at, $this->current->parse($start_date)->subDays(1));
            $bfCr = $this->generalledger->getCrBalanceBroughtForward($firstGl->created_at, $this->current->parse($start_date)->subDays(1));
            $reports = $this->generalledger->getReports($start_date, $end_date);
            session()->flash("success", "<strong>Success!</strong> Trial balance report generated.");
            return view('accounting.reports.trial-balance', ['reports'=> $reports, 'status'=>1, 'bfDr'=>$bfDr, 'bfCr'=>$bfCr,
                'from'=>$start_date, 'to'=>$end_date]);
        }else{
            session()->flash("error", "Whoops! There have been no financial transaction since inception.");
            return back();
        }
    }


    public function showBalanceSheet(){
        return view('accounting.reports.balance-sheet', ['status'=>0]); //status = 0 => no accounting period entered
    }

    public function balanceSheet(Request $request){
        $this->validate($request, [
            'date'=>'required|date'
        ]);
        $this->current = Carbon::now();
        $date = $request->date;
        $firstGl = $this->generalledger->getFirstGlTransaction();
        if(!empty($firstGl)){
            $bfDr = $this->generalledger->getDrBalanceBroughtForward($firstGl->created_at, $this->current->parse($date)->subDays(1));
            $bfCr = $this->generalledger->getCrBalanceBroughtForward($firstGl->created_at, $this->current->parse($date)->subDays(1));
            $reports = $this->generalledger->getBalanceSheetReports($date);
            $revenue = $this->generalledger->getRevenue($date);
            $expense = $this->generalledger->getExpenses($date);
            session()->flash("success", "Action successful.");
            return view('accounting.reports.balance-sheet', [
                'reports'=> $reports, 'status'=>1,
                'bfDr'=>$bfDr, 'bfCr'=>$bfCr,
                'date'=>$date,
                'revenue'=>$revenue,
                'expense'=>$expense
            ]);
        }else{
            session()->flash("error", "Whoops! There have been no financial transaction since inception.");
            return back();
        }
    }


    public function showProfitOrLoss(){
        return view('accounting.reports.profit-or-loss', ['status'=>0]); //status = 0 => no accounting period entered
    }

    public function profitOrLoss(Request $request){
        $this->validate($request, [
            'start_date'=>'required|date',
            'end_date'=>'required|date|after_or_equal:start_date'
        ]);
        $this->current = Carbon::now();
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $firstGl = $this->generalledger->getFirstGlTransaction();
        if(!empty($firstGl)){
            $bfDr = $this->generalledger->getDrBalanceBroughtForward($firstGl->created_at, $this->current->parse($start_date)->subDays(1));
            $bfCr = $this->generalledger->getCrBalanceBroughtForward($firstGl->created_at, $this->current->parse($start_date)->subDays(1));
            $reports = $this->generalledger->getReports($start_date, $end_date);
            $revenue = $this->generalledger->getRevenueByDateRange($start_date, $end_date);
            $expense = $this->generalledger->getExpensesByDateRange($start_date, $end_date);
            session()->flash("success", "Action successful.");
            return view('accounting.reports.profit-or-loss', [
                'reports'=> $reports, 'status'=>1,
                'bfDr'=>$bfDr, 'bfCr'=>$bfCr,
                'from'=>$start_date,
                'to'=>$end_date,
                'revenue'=>$revenue,
                'expense'=>$expense
            ]);
        }else{
            session()->flash("error", "Whoops! There have been no financial transaction since inception.");
            return back();
        }
    }

    public function showJournalVoucher(){

        return view('accounting.reports.journal-voucher', ['accounts'=>$this->chartofaccounts->getAllDetailChartOfAccounts()]);
    }

    public function setNewJournalEntry(Request $request)
    {
        $request->validate([
            'issue_date'=>'required',
            'entry_no'=>'required',
            'account'=>'required|array',
            'account.*'=>'required'
        ]);
        $cr_total = 0;
        $dr_total = 0;

        for($i = 0; $i<count($request->debit_amount); $i++){
            $cr_total += $request->credit_amount[$i];
            $dr_total += $request->debit_amount[$i];
        }
        if(count($request->account) != count($request->debit_amount)){
            session()->flash("error", "Whoops! Something went wrong. Try again.");
            return back();
        }

        if($cr_total == $dr_total){
            $this->journalvoucher->setNewJournalVoucher($request);
            session()->flash("success", "Action successful");
            return back();
        }else{
            session()->flash("error", "Whoops! The value of DR must be same with CR. Try again.");
            return redirect()->route('journal-entries');
        }
    }
}
