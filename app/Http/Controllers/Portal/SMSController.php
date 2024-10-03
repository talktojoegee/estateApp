<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ServicesController;
use App\Http\Traits\SMSServiceTrait;
use App\Http\Traits\UtilityTrait;
use App\Models\ActivityLog;
use App\Models\BulkMessage;
use App\Models\BulkSmsAccount;
use App\Models\BulkSmsFrequency;
use App\Models\CashBookAccount;
use App\Models\Message;
use App\Models\PhoneGroup;
//use App\Models\Tenant;
use App\Models\SenderId;
use App\Models\TransactionCategory;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yabacon\Paystack;
use Yabacon\Paystack\Fee;

class SMSController extends Controller
{
    use SMSServiceTrait, UtilityTrait;

    public $api;
    public $baseUrl;
    public $apiToken;
    /*public $airTelNonDndArray = [], $airTelDndArray = [];
    public $mtnNonDndArray = [], $mtnDndArray = [];
    public $gloNonDndArray = [], $gloDndArray = [];
    public $mobileNonDndArray = [], $mobileDndArray = []; //9mobile*/
    public function __construct()
    {
        $this->adminApiToken = env('SMARTSMS_API_TOKEN');
        $this->baseUrl = 'https://app.smartsmssolutions.com/';
        $this->apiToken = env('SMARTSMS_API_TOKEN');

        //$this->middleware('auth');
        $this->bulksmsaccount = new BulkSmsAccount();
        $this->phonegroup = new PhoneGroup();
        $this->bulkmessage = new BulkMessage();
        $this->service = new ServicesController();
        $this->senderid = new SenderId();

        $this->cashbookaccounts = new CashBookAccount();
        $this->transactioncategory = new TransactionCategory();
        $this->message = new Message();

    }

    public function dateTest(){
        $next = $this->getRecurringNextMonth(BulkSmsFrequency::find(10), '12:00');
        //$next = $this->getRecurringNextWeek(BulkSmsFrequency::find(1));
        //$timeLot = '10:00:00';
        //$n = $next;//.' '. $timeLot;
        return dd($next);
        //return dd($date);
    }

    public function showPhoneGroupForm(){

        return view('bulksms.phone-groups',[
            'groups'=>$this->phonegroup->getAllUserPhoneGroups()
        ]);
    }

    public function setNewPhoneGroup(Request $request){
        $this->validate($request,[
            'group_name'=>'required',
            'phone_numbers'=>'required'
        ],[
            'group_name.required'=>'Enter a phone group name',
            'group_name.unique'=>"There's an existing phone group with this name",
            'phone_numbers.required'=>'Enter phone numbers'
        ]);
        $filtered_numbers = [];
        $phone_number_array = preg_split("/, ?/",$request->phone_numbers);
        for($i = 0; $i<count($phone_number_array); $i++){
            $number = $this->appendCountryCode($phone_number_array[$i]);
            array_push($filtered_numbers,str_replace(' ', '', $number));
        }
        $filter = array_unique($filtered_numbers);
        $phone_numbers = implode(",",$filter);
        $this->phonegroup->setNewPhoneGroup($request, $phone_numbers);
        session()->flash("success", "Your phone group was successfully published.");
        return back();
    }

    public function updatePhoneGroup(Request $request){
        $this->validate($request,[
            'group_name'=>'required',
            'phone_numbers'=>'required',
            'group'=>'required'
        ],[
            'group_name.required'=>'Enter a phone group name',
            'group_name.unique'=>"There's an existing phone group with this name",
            'phone_numbers.required'=>'Enter phone numbers'
        ]);
        $filtered_numbers = [];
        $phone_number_array = preg_split("/, ?/",$request->phone_numbers);
        for($i = 0; $i<count($phone_number_array); $i++){
            $number = $this->appendCountryCode($phone_number_array[$i]);
            array_push($filtered_numbers,$number);
        }
        $filter = array_unique($filtered_numbers);
        $phone_numbers = implode(",",$filter);
        $this->phonegroup->updatePhoneGroup($request, $phone_numbers);
        session()->flash("success", "Your changes were saved.");
        return back();
    }

    public function showTopUpForm(){
        $branchId = Auth::user()->branch;
        return view('bulksms.top-up',[
            'transactions'=>$this->bulksmsaccount->getBulkSmsTransactions(),
            'balance'=>$this->getWalletBalance(),
            'accounts'=>$this->cashbookaccounts->getBranchAccounts($branchId),
            'categories'=>$this->transactioncategory->getBranchCategoriesByType($branchId, 2),
        ]);
    }

    public function showBulksmsMessages(){
        return view('bulksms.messages',[
            'messages'=>$this->bulkmessage->getAllMessages()
        ]);
    }

    public function updateMessageStatus(Request $request){
        $this->validate($request,[
            'messageId'=>'required',
            'status'=>'required'
        ],[
            'messageId.required'=>'',
            'status.required'=>''
        ]);
        $message = $this->bulkmessage->getMessageById($request->messageId);
        if(!empty($message)){
            $message->recurring_active = $request->status;
            $message->save();
        }
        session()->flash("success", "Action successful");
        return back();
    }
/*
    public function processTopUpRequest(Request $request){
        $this->validate($request,[
            'amount'=>'required',
            //'expenseCategory'=>'required',
            //'account'=>'required',
        ],[
            'amount.required'=>"How much will you like to add?" ,
            //'expenseCategory.required'=>"Select an expense category" ,
            //'account.required'=>"Which account is funding this purchase?" ,
        ]);
        try{
            $paystack = new Paystack(env('PAYSTACK_SECRET_KEY'));
            $cost = $request->amount;
            $builder = new Paystack\MetadataBuilder();
            $builder->withCost($cost);
            $builder->withUser(Auth::user()->id);

            $builder->withTransaction(4);
            $builder->withAccount($request->account);
            $builder->withCategory($request->expenseCategory);
            $metadata = $builder->build();
            $charge = $cost < 2500 ? ceil($cost*1.7/100) : ceil($cost*1.7/100)+100;
            $tranx = $paystack->transaction->initialize([
                'amount'=>($cost+$charge)*100,       // in kobo
                'email'=>Auth::user()->email,
                'reference'=>substr(sha1(time()),23,40),
                'metadata'=>$metadata
            ]);
            return redirect()->to($tranx->data->authorization_url)->send();
        }catch (Paystack\Exception\ApiException $exception){
            session()->flash("error", "Whoops! Something went wrong. Try again.");
            return back();
        }
    }*/
    public function processTopUpRequest(Request $request){
        $this->validate($request,[
            'amount'=>'required',
            'charge'=>'required'
        ],[
            'amount.required'=>"How much will you like to add?" ,
            'charge.required'=>"Specify charge" ,
        ]);
        $note = "Purchase of bulk SMS units. The amount includes convenience fee";
        $reference = $request->trans ??  substr(sha1(time()),29,40);
        $this->bulksmsaccount->creditAccount($reference,
            $request->amount, //50900
            ($request->amount + $request->charge), Auth::user()->id);

        /*$this->cashbook->addCashBook($branchId, $category, $account,
            $defaultCurrency, 2, 0, 2, now(),
            $note, $note,
            ($tranx->data->amount + $tranx->data->metadata->cost)/100,  0,
            substr(sha1(time()),31,40),date('m', strtotime(now())),date('Y', strtotime(now())));*/

        return response()->json(['message'=>"Thank you! Your payment was received!"],200);
    }

    public function showTopUpTransactions(){
        return view('bulksms.top-up-transactions',[
            'balance'=>$this->getWalletBalance()
        ]);
    }

    public function showComposeMessageForm(){
        //if(empty(Auth::user()->getUserPhoneGroups->count() <= 0 )){
            //session()->flash("error", "You'll need to quickly setup your <strong>sender ID</strong> in order to use this service. Click the <strong>Bulk SMS</strong> tab below.");
            //return redirect()->route('create-senders');
        //}else{
            return view('bulksms.compose-sms',[
                'phonegroups'=>$this->phonegroup->getAllUserPhoneGroups()
            ]);
        //}
    }

    public function previewMessage(Request $request){
        //return dd($request->all());
        $this->validate($request, [
            'message'=>'required',
            'senderId'=>'required',
            'type'=>'required'
        ],[
            'message.required'=>'Enter the content of your message in the box provided above.',
            'senderId.required'=>'Select sender ID'
        ]);
        if($request->type == 2){ //schedule message
            if(empty($request->timeLot) && empty($request->dateTime) && empty($request->frequency)){
                session()->flash("error", "Whoops! Choose date & time to schedule SMS");
                return back();
            }
        }
        $list = [];
        if(empty($request->phonegroup) && empty($request->phone_numbers)){
            session()->flash("error", "Whoops! Kindly select the source of your contact or enter phone numbers in the box provided; separating them with comma.");
            return back();
        }else{
            #Phonegroup
            if(!empty($request->phonegroup)){
                $phonegroups = $this->phonegroup->getPhoneGroupArrayById($request->phonegroup);
                $contact_array = [];
                foreach ($phonegroups as $phonegroup ){
                    $ex = explode(",", $phonegroup->phone_numbers);
                    for($i = 0; $i<count($ex); $i++){
                        array_push($list, str_replace(' ','',$ex[$i]));
                    }
                }
            }
            #Phone numbers
            if(!empty($request->phone_numbers)){
                $filtered_numbers = [];
                $phone_number_array = preg_split("/, ?/",$request->phone_numbers);
                for($i = 0; $i<count($phone_number_array); $i++){
                    $number = $this->appendCountryCode($phone_number_array[$i]);
                    array_push($list,str_replace(' ', '', $number));
                }
            }
            $filter = array_unique($list);
            $phone_numbers = implode(",",$filter);
            $persons = count($filter);
            $no_of_pages = round(strlen($request->message)/160) < 1 ? 1 : round(strlen($request->message)/160);

            try{
                $batchMax = 500;
                $grandTotal = $this->getBulkSMSCharge($list, $request->message, $batchMax );

                return view('bulksms.preview-message',[
                    'cost'=>$grandTotal,
                    'balance'=>$this->getWalletBalance(),
                    'persons'=>$persons,
                    'transactions'=>$this->bulksmsaccount->getBulkSmsTransactions(),
                    'pages'=>$no_of_pages,
                    'message'=>$request->message,
                    'phone_numbers'=>$phone_numbers,
                    'senderId'=>$request->senderId,
                    'type'=>$request->type,
                    'dateTime'=>$request->dateTime ?? null,
                    'frequency'=>$request->frequency ?? null,
                    'timeLot'=>$request->timeLot ?? null,
                    'recurring'=>isset($request->recurring) ? 1 : 0,
                    'phoneGroup'=>$request->phonegroup ?? null,
                ]);
            }catch (\Exception $exception){
                return dd($exception->getMessage());
            }

        }

    }

    public function inlinePreviewMessage(Request $request){
        $this->validate($request, [
            'message'=>'required',
            'senderId'=>'required',
            'client'=>'required',
            'phone_numbers'=>'required',
        ],[
            'message.required'=>'Enter the content of your message in the box provided above.',
            'senderId.required'=>'Select sender ID'
        ]);
        $list = [];
        if(empty($request->phone_numbers)){
            session()->flash("error", "Whoops! Kindly select the source of your contact or enter phone numbers in the box provided; separating them with comma.");
            return back();
        }else{
            #Phone numbers
            if(!empty($request->phone_numbers)){
                $filtered_numbers = [];
                $phone_number_array = preg_split("/, ?/",$request->phone_numbers);
                for($i = 0; $i<count($phone_number_array); $i++){
                    $number = $this->appendCountryCode($phone_number_array[$i]);
                    array_push($list,str_replace(' ', '', $number));
                }
            }
            $filter = array_unique($list);
            $phone_numbers = implode(",",$filter);
            $persons = count($filter);
            $no_of_pages = round(strlen($request->message)/160) < 1 ? 1 : round(strlen($request->message)/160);
            try{
                $batchMax = 500;
                $grandTotal =  $this->getBulkSMSCharge($list, $request->message, $batchMax );
                return view('followup.partial._preview-message',[
                    'cost'=>$grandTotal,
                    'balance'=>$this->getWalletBalance(),
                    'persons'=>$persons,
                    'transactions'=>$this->bulksmsaccount->getBulkSmsTransactions(),
                    'pages'=>$no_of_pages,
                    'message'=>$request->message,
                    'phone_numbers'=>$phone_numbers,
                    'senderId'=>$request->senderId,
                    'client'=>$request->client,
                ]);
            }catch (\Exception $exception){
                return dd($exception->getMessage());
            }

        }

    }


    public function sendTextMessage(Request $request){
        $this->validate($request,[
            'message'=>'required',
            'cost'=>'required',
            'pages'=>'required',
            'persons'=>'required',
            'phone_numbers'=>'required',
            'senderId'=>'required',
            'type'=>'required'
        ]);
        try {
            $phoneGroups = null;
            if(isset($request->phoneGroup)){
                $phoneGroups = json_encode($this->getIntegerArray($request->phoneGroup));
            }
            $senderId = $request->senderId;
            $units = round($request->persons);
            if(isset($request->type) && ($request->type == 1)){ // instant messaging
                $send = $this->service->sendSmartSms($senderId, $request->phone_numbers, $request->message, 0, Auth::user()->id);
                if(!empty($send->code == 1000)){
                    $this->bulksmsaccount->debitAccount(substr(sha1(time()),27,40), $request->cost, $units);
                    $this->bulkmessage->setNewMessage($request->message, $request->phone_numbers,
                        $senderId, 1, now(), now(), null, 0, $phoneGroups);

                    if($request->thirdParty == 1){
                        $this->message->saveTextMessage('SMS Message', $request->message, (array)$request->client);
                        $log = Auth::user()->first_name.' '.Auth::user()->last_name.' sent an SMS';
                        ActivityLog::registerActivity(1,$request->client,Auth::user()->id, $request->client, 'SMS Sent', $log);
                        session()->flash("success", "Your text message was sent successfully. Currently awaiting delivery.");
                        return back();
                    }
                    session()->flash("success", "Your text message was sent successfully. Currently awaiting delivery.");
                    return redirect()->route('compose-sms');
                }else{
                    session()->flash("error", "Something went wrong. Please try again later or contact admin.");
                    return back();
                }
            }else{ //schedule message
                /**
                 * Only debit account when schedule SMS is sent
                 */
                //$this->bulksmsaccount->debitAccount(substr(sha1(time()),27,40), $request->cost, $units);
                if(isset($request->recurring) && ($request->recurring == 1) ){
                    $frequency = BulkSmsFrequency::getBulkFrequencyById($request->frequency);
                    if(!empty($frequency)){
                        switch ($frequency->letter){
                            case 'd':
                                $nextScheduleDate = $this->getRecurringNextWeek($frequency);
                                $nextDate = $nextScheduleDate->format("Y-m-d $request->timeLot");
                                $this->bulkmessage->setNewMessage($request->message, $request->phone_numbers,
                                    $senderId, 0, $nextDate, $nextDate, $frequency->id, 1, $phoneGroups);
                            break;
                            case 'm':
                                $nextScheduleDate = $this->getRecurringNextMonth($frequency, $request->timeLot);
                                $this->bulkmessage->setNewMessage($request->message, $request->phone_numbers, $senderId, 0,
                                    $nextScheduleDate, $nextScheduleDate, $frequency->id, 1, $phoneGroups);
                            break;
                            case 'o':
                                //do nothing yet
                            break;
                        }
                        session()->flash("success", "Action successful!");
                        return back();
                    }else{
                        session()->flash("error", "Whoops! Something went wrong.");
                        return back();
                    }
                }else{
                    $startDate = new \DateTime($request->dateTime);
                    $this->bulkmessage->setNewMessage($request->message, $request->phone_numbers, $senderId, 0,
                        $startDate, $startDate, null, 2, $phoneGroups);
                    session()->flash("success", "Action successful!");
                    return back();
                }

            }

        }catch (\Exception $exception){
            session()->flash("error", "Something went wrong. Please try again later or contact admin.".$exception);
            return back();
            //return redirect()->route('compose-sms');
        }

    }


    public function showScheduleSmsForm(){
        /*
         * $test = new DateTime('2024-05-01 10:00');
echo $test->modify('third tuesday ' . $test->format('H:i'))->format('Y-m-d H:i');
         */
        return view('bulksms.schedule-sms',[
            'frequencies'=>BulkSmsFrequency::getBulkSmsFrequencies()
        ]);
    }

    public function showApiInterface(){
        return view('bulksms.api-settings');
    }
    public function getBulkMessages(){
        return view('sms.bulk-messages',[
            'messages'=>$this->bulkmessage->getTenantMessages()
        ]);
    }

    public function viewBulkMessage($slug){
        $message = $this->bulkmessage->getTenantMessageBySlug($slug);
        if(!empty($message)){
            return view('bulksms.view-bulk-sms', ['message'=>$message]);
        }else{
            session()->flash("error", "No record found.");
            return back();
        }
    }

    public function checkFirstDigit($number){
        $digit = substr($number, 0,1);
        if($digit == '0'){
            return '0'; //first number starts with 0
        }elseif($digit == '+'){
            return '+';
        }else{
            return 's'; //some #
        }
    }

    public function appendCountryCode($number){
        $country_code = "234";
        $phone_no = "";
        $digit = $this->checkFirstDigit($number);
        $length = strlen($number);
        if($digit == '0'){
            #Remove the 0
            $stripped_phone = substr($number,1,$length - 1);
            $phone_no = $country_code.$stripped_phone;
            return $phone_no;
        }elseif( $digit == 's'){ //2348032404359, 08032889972, 7036005031, +2349023849871
            if(substr($number,0,3) == '234'){
                return $number;
            }else{
                return $country_code.$number;
            }
        }elseif($digit == '+'){
            return substr($number,1,$length-1);
        }
    }

    public function showSenderIdForm(){
        return view('bulksms.create-sender');
    }

    public function createSenderId(Request $request){
        $this->validate($request,[
            'sender_id'=>'required',
            'purpose'=>'required'
        ],[
            'sender_id.required'=>'Enter sender ID',
            'purpose.required'=>"Enter a brief description of what you'll be using this sender ID to do."
        ]);
        $this->senderid->createSenderId($request);
       /* try{
            $this->senderIdRequest($request->sender_id, $request->purpose);
            //$this->service->senderIdRequest($request->sender_id, $request->purpose);
        }catch (\Exception $ex){

        }*/
        session()->flash("success", "Sender ID registered. You can use <strong>SMS Channel</strong> to send bulk SMS while you wait for your sender ID to be approved.");
        return back();
    }

    public function showRegisteredSenderIds(){
        return view('bulksms.all-sender-ids');
    }

    public function batchReport(){
        return view('bulksms.batch-report');
    }
}
