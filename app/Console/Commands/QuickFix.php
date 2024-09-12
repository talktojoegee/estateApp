<?php

namespace App\Console\Commands;

use App\Http\Traits\UtilityTrait;
use App\Models\Estate;
use App\Models\InvoiceMaster;
use App\Models\Property;
use App\Models\Receipt;
use App\Models\Wallpaper;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class QuickFix extends Command
{
    use UtilityTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quick:fix';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        /*$properties = Property::all();
        foreach($properties as $key=> $property){
            $estate = Estate::getEstateById($property->estate_id);
            if(!empty($estate)){
                $code = $estate->e_ref_code.$this->padNumber($property->id,6);
                //$property->slug = Str::slug($property->property_name)."_".substr(sha1( (time()+$key) ),29,40);
                $property->property_code = $code;
                $property->save();
            }

        }*/
        /*$invoices = InvoiceMaster::all();
       foreach($invoices as $key=> $invoice){
           $invoice->ref_no = substr(sha1((time() + $key)),32,40);
           $invoice->slug = substr(sha1( (time()+$key) ),29,40);
           $invoice->save();
       }*/
        /*$invoices = Receipt::all();
       foreach($invoices as $key=> $invoice){
           $invoice->trans_ref = substr(sha1((time() + $key)),32,40);

           $invoice->save();
       }*/
        //return Command::SUCCESS;

        /*$wallpaperFilenames = [
            '5f69f21975183_1600778777_20200922.jpg',
            '5f69f3d2ba47a_1600779218_20200922.jpg',
            '5f69f418d78f4_1600779288_20200922.jpg',
            '5f69f444c3875_1600779332_20200922.jpg',
            '5f69f48b5ff58_1600779403_20200922.jpg',
            '5f69f4a0ced0b_1600779424_20200922.jpg',
            '5f69f4cb380de_1600779467_20200922.jpg',
            '5f69f4eb4e4d2_1600779499_20200922.jpg',
            '5f69f508112f3_1600779528_20200922.jpg',
            '5f6a77aad1dd3_1600812970_20200922.jpg',
            '5f6a77f290350_1600813042_20200922.jpg',
            '5f6a957dac0da_1600820605_20200923.jpg',
            '5f6a961d78a74_1600820765_20200923.jpg',
            '5f6a97082892a_1600821000_20200923.jpg',
            '5f6a976338832_1600821091_20200923.jpg',
            '5f6b389c70b74_1600862364_20200923.jpg',
            '5f6b38b9ef556_1600862393_20200923.jpg',
            '5f6b38d74ff1a_1600862423_20200923.jpg',
            '5f6b38f07596a_1600862448_20200923.jpg',
            '5f6b390087819_1600862464_20200923.jpg',
            '5f6b391469fbc_1600862484_20200923.jpg',
            '5f6b3927e62b9_1600862503_20200923.jpg',
            '5f6b393cc7cbc_1600862524_20200923.png',
            '5f6b3956cad53_1600862550_20200923.jpg',
            '5f6b3976e86d9_1600862582_20200923.jpg',
            '5f6b398c1e8d9_1600862604_20200923.jpg',
            '5f6b399cbb457_1600862620_20200923.jpg',
            '5f6dff78db03a_1601044344_20200925.jpg',
            '5f7af7e0da711_1601894368_20201005.jpeg',
            '5f897a9a0b192_1602845338_20201016.jpeg',
            '5fbbbf66c2728_1606139750_20201123.jpeg',
            '60393a1c2336b_1614363164_20210226.jpg',
            '61389dda51b97_1631100378_20210908.JPG',
        ];
        $names = [
            'Volcano',
            'Relationship tour',
            'Green Leave',
            'Flying Owl',
            'Tortoise',
            'Programming Laptop',
            'Accounting',
            'Hand in Water',
            'Colorful Sea',
            'Sand',
            'Default',
            'Custom Background 0001',
            'Custom Background 464.',
            'Custom Background 630.',
            'Love Hearts',
            'Butterfly background',
            'Glittery Artificial Christmas light',
            'Christmas Bauble',
            'Christmas Bauble 2',
            'Decorated',
            'Home Workspace',
            'Dove',
            'Elephant Artwork',
            'Colorful Background',
            'Pot Flower',
            'Heart, Key & Padlock',
            'Love Maths',
            'Custom Background 761.',
            'Custom Background 928.',
            'Custom Background 601.',
            'Custom Background 138.',
            'Custom Background 303.',
            'Custom Background 605 ',
        ];
        foreach($wallpaperFilenames as $key => $paper){
            echo $paper."\n";
            Wallpaper::create([
                'wallpaper_name'=>$names[$key],
                'text_color'=>'#ffffff',
                'caption_color'=>'#ffffff',
                'uploaded_by'=>null,
                'custom'=>0,
                'filename'=>$paper,
            ]);
        }*/

       /* $allottee = [
            'First',
        ]*/
    }
}
