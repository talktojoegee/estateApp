<?php
namespace App\Http\Traits;

use App\Models\EmailQueue;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Markdown;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

trait UtilityTrait {

    public function getIntegerArray($arr){
        $values = [];
        for($i = 0; $i<count($arr); $i++){
            array_push($values, (int) $arr[$i]);
        }
        return $values;
    }

    public function getPostType($typeId){
        $type = '';
        switch ($typeId){
            case 1:
                $type = 'Message';
                break;
            case 2:
                $type = 'Memo';
                break;
            case 3:
                $type = 'Announcement';
                break;
            case 4:
                $type = 'Directive';
                break;
            case 5:
                $type = 'Project';
                break;
            case 6:
                $type = 'Expense request';
                break;
            case 7:
                $type = 'Expense report';
                break;
            case 8:
                $type = 'Attendance';
                break;
            case 9:
                $type = 'Event';
                break;
        }
        return $type;
    }

    public function getRecurringNextWeek($frequency){
        $date = Carbon::parse(date("Y-m-d"));
         switch($frequency->value){
             case 0: //Sunday
                 $result = $date->is('Sunday') ? $date : $date->next('Sunday');
                 break;
             case 1: //Monday
                 $result = $date->is('Monday') ? $date : $date->next('Monday');
                 break;
             case 2: //Tuesday
                 $result = $date->is('Tuesday') ? $date : $date->next('Tuesday');
                 break;
             case 3: //Wednesday
                 $result = $date->is('Wednesday') ? $date : $date->next('Wednesday');
                 break;
             case 4: //Thursday
                 $result = $date->is('Thursday') ? $date : $date->next('Thursday');
                 break;
             case 5: //Friday
                 $result = $date->is('Friday') ? $date : $date->next('Friday');
                 break;
             case 6: //Saturday
                 $result = $date->is('Saturday') ? $date : $date->next('Saturday');
                 break;
         }
         return $result;
    }

    public function getRecurringNextMonth($frequency, $timeLot){
        $currentDate = new \DateTime(date('Y-m-d'));
        $nextMonth = $currentDate->modify('+1 Month');
        return $nextMonth->modify($frequency->value)->format("Y-m-d $timeLot");

    }

    public function redirectSuccess(){
        session()->flash("success", "Action successful!");
        return back();
    }
    public function redirectError(){
        session()->flash("error", "Whoops! Something went wrong.");
        return back();
    }

    public function uploadFile(Request $request)
    {
        if ($request->hasFile('attachment')) {
            $extension = $request->attachment->getClientOriginalExtension();
            $filename = uniqid() . '_' . time() . '_' . date('Ymd') . '.' . $extension;
            $dir = 'assets/drive/cloud/';
            $request->attachment->move(public_path($dir), $filename);
           return $filename;
        }

    }


    public function sendEmail(View $view = null, Model $model = null, Markdown $markdown = null){

    }

    public function queueEmailForSending($userIds, $subject, $message){
        foreach($userIds as $id){
            EmailQueue::queueEmail($id, $subject, $message);
        }
    }

    function padNumber($number, $length) {
        if ($number < 20000) {
            // Pad the number with leading zeros
            return str_pad($number, $length, '0', STR_PAD_LEFT);
        }
        return $number;  // Return the number as is if it's 20000 or greater
    }

    function numToOrdinalWord($num)
    {
        $first_word = array('eth','First','Second','Third','Fourth','Fifth','Sixth','Seventh','Eighth','Ninth','Tenth','Eleventh','Twelfth','Thirteenth','Fourteenth','Fifteenth','Sixteenth','Seventeenth','Eighteenth','Nineteenth','Twentieth');
        $second_word =array('','','Twenty','Thirthy','Forty','Fifty');

        if($num <= 20)
            return $first_word[$num];

        $first_num = substr($num,-1,1);
        $second_num = substr($num,-2,1);

        return $string = str_replace('y-eth','ieth',$second_word[$second_num].'-'.$first_word[$first_num]);
    }

    public function handleLedgerPosting($debit, $credit, $glcode, $narration = 'N/A',
                                        $ref, $bank, $postedBy, $transDate ){

            $data = [
                'glcode'=>$glcode,
                'posted_by'=>$postedBy,
                'narration'=>$narration,
                'dr_amount'=>$debit,
                'cr_amount'=>$credit,
                'ref_no'=>$ref,//strtoupper(substr(sha1(time()), 29,40)),
                'bank'=>$bank,
                'transaction_date'=>$transDate,
                'ob'=>0,
                'created_at'=>now(),
                'updated_at'=>now(),
            ];
            #Register transaction
            DB::table('general_ledgers')->insert($data);
    }
}
?>
