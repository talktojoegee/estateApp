<?php

namespace App\Console\Commands;

use App\Http\Controllers\ServicesController;
use App\Http\Traits\SMSServiceTrait;
use App\Http\Traits\UtilityTrait;
use App\Models\BulkMessage;
use App\Models\BulkSmsAccount;
use App\Models\BulkSmsFrequency;
use App\Models\BulkSmsRecurringLog;
use App\Models\PhoneGroup;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class SendSMSCommand extends Command
{
    use UtilityTrait;
    use SMSServiceTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bulksms:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send bulk sms';



    /**
     * Execute the console command.
     *
     * @return int
     * @throws \Exception
     */
    public function handle()
    {


        /*
         * This section handles scheduled SMS messages
         *
         */
        $now = Carbon::now('Africa/Lagos')->toDateTimeString();
        $messages = BulkMessage::getRecurringMessages();
        if(count($messages) > 0){
            foreach($messages as $message){
               // echo "\n Base URL:: ".env('SMARTSMS_BASEURL')."\n";
                //echo "\n API Token:: ".env('SMARTSMS_API_TOKEN')."\n";
                $currentDate = new \DateTime(date("Y-m-d"));
                $scheduledDate = new \DateTime(date("Y-m-d", strtotime($message->next_schedule)));
                if($currentDate == $scheduledDate){
                    $currentTime = date('H:i', strtotime($now));
                    $messageTime = date('H:i', strtotime($message->next_schedule));
                    echo "\n Current Time:". $currentTime." | \t Message Time: ".$messageTime." | \t Date:".$currentDate->format('d M, Y')." | \t Status: ".($currentTime === $messageTime ? 'Same' : 'Not same');
                    if($currentTime === $messageTime){

                        //Get church current bulk sms balance
                        $balance = $this->getWalletBalance();
                        //$message->getUserAccount->sum('credit') - $message->getUserAccount->sum('debit');
                        //echo "Balance: $balance \n";
                        /**
                         * Update list of receivers
                         * if scheduled message is from a phone group
                         **/
                        if(!(is_null($message->phone_group))){
                            $init_group = null;
                            foreach(json_decode($message->phone_group) as $pg){
                                $group = PhoneGroup::getPhoneGroupById($pg);
                                $init_group .= $group->phone_numbers.",";
                            }
                            $message->sent_to = $init_group;
                            $message->save();
                        }

                        /**
                         * calculate cost
                         **/
                        #Phone numbers
                        $list = [];
                        if(!empty($message->sent_to)){
                            $phone_number_array = preg_split("/, ?/",$message->sent_to);
                            for($i = 0; $i<count($phone_number_array); $i++){
                                $number = $this->appendCountryCode($phone_number_array[$i]);
                                array_push($list,str_replace(' ', '', $number));
                            }
                        }
                        try {
                            $filter = array_unique($list);
                            $persons = count($filter);
                            $batchMax = 500;
                            $totalCost =  $this->getBulkSMSCharge($list, $message->message, $batchMax );

                            //echo "\t Cost: $totalCost ";
                            //echo "\t Balance: $balance ";

                            if($balance > $totalCost){
                                $units = round($persons);
                                //echo "\n Before service call: \n";
                                //echo "ID:: $message->sender_id To:: $message->sent_to Message:: $message->message Code:: $message->batch_code \n";

                                $response = ServicesController::sendStaticSmartSms($message->sender_id, $message->sent_to, $message->message, 0, $message->batch_code);
                                if($response->code >= 1000){
                                    /**
                                     * Charge bulk sms wallet/account
                                     */
                                    BulkSmsAccount::staticDebitAccount($message->user_id, substr(sha1(time()),27,40), $totalCost, $units);
                                }
                                if($message->recurring == 2){ //scheduled for a specific time
                                    $message->recurring_active = 0;
                                    $message->save();
                                }else{
                                    //For recurring messages
                                    if(!is_null($message->bulk_frequency)){
                                        $frequency = BulkSmsFrequency::getBulkFrequencyById($message->bulk_frequency);
                                        if(!empty($frequency)){
                                            switch ($frequency->letter){
                                                case 'd':
                                                    $timeLot = date('h:i', strtotime($message->next_schedule));
                                                    $nextScheduleDate = $this->getRecurringNextWeek($frequency);
                                                    $nextDate = $nextScheduleDate->format("Y-m-d $timeLot");
                                                    $message->recurring_active = 1;
                                                    $message->next_schedule = $nextDate;
                                                    $message->save();
                                                    //Log
                                                    BulkSmsRecurringLog::newSmsLog($message->id, $message->message,
                                                        $message->sent_to, $message->batch_code);
                                                    break;
                                                case 'm':
                                                    $timeLot = date('h:i', strtotime($message->next_schedule));
                                                    $nextScheduleDate = $this->getRecurringNextMonth($frequency, $timeLot);
                                                    $nextDate = $nextScheduleDate->format("Y-m-d $timeLot");
                                                    $message->recurring_active = 1;
                                                    $message->next_schedule = $nextDate;
                                                    $message->save();
                                                    //Log
                                                    BulkSmsRecurringLog::newSmsLog($message->id, $message->message,
                                                        $message->sent_to, $message->batch_code);
                                                    break;
                                                case 'o':
                                                    //do nothing yet
                                                    break;
                                            }
                                        }
                                    }
                                }
                            }


                        }catch (\Exception $exception){

                        }



                    }
                }
            }

        }





    }



    public function sendSmartSms($senderId, $to, $message, $messageType, $refId ){

        $client = new Client();
        $options = [
            'multipart' => [
                [
                    'name' => 'token',
                    'contents' => env("SMARTSMS_API_TOKEN")
                ],
                [
                    'name' => 'sender',
                    'contents' => $senderId
                ],
                [
                    'name' => 'to',
                    'contents' => $to
                ],
                [
                    'name' => 'message',
                    'contents' => $message
                ],
                [
                    'name' => 'type',
                    'contents' => $messageType
                ],
                [
                    'name' => 'routing',
                    'contents' => 3
                ],
                [
                    'name' => 'ref_id',
                    'contents' => 1,//Auth::user()->id
                ],

            ]];
        $url = env("SMARTSMS_BASEURL")."io/api/client/v1/sms/";
        $request = new \GuzzleHttp\Psr7\Request('POST', $url);
        $res = $client->sendAsync($request, $options)->wait();
        return json_decode($res->getBody()->getContents());
    }



}
