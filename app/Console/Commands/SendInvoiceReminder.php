<?php

namespace App\Console\Commands;

use App\Http\Controllers\ServicesController;
use App\Http\Traits\SMSServiceTrait;
use App\Models\BulkSmsAccount;
use App\Models\InvoiceMaster;
use App\Models\Lead;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendInvoiceReminder extends Command
{
    use SMSServiceTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will send SMS reminders of invoices whose due is within 7 days.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $balance = $this->getWalletBalance();
        $message = "Hello there! We trust this message meets you well. The invoice that issued to you will expire in 7 days. Do well to pay in time.";

        $invoices = InvoiceMaster::whereBetween('due_date', [Carbon::now(), Carbon::now()->addDays(7)])->get();
        if(count($invoices) > 0){
            foreach($invoices as $invoice){
                $customer = Lead::find($invoice->customer_id);
                if(!empty($customer)){
                    if($balance > 3.5){ //per page charge
                        $response = ServicesController::sendStaticSmartSms('EFAB Properties', $customer->phone, $message, 0, substr(sha1(time()),29,40));
                        if($response->code >= 1000){
                            /**
                             * Charge bulk sms wallet/account
                             */
                            BulkSmsAccount::staticDebitAccount($message->user_id, substr(sha1(time()),27,40), 3.5, 3.5);
                        }
                    }
                }
            }
        }

        //return Command::SUCCESS;
    }
}
