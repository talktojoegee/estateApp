<?php

namespace App\Console\Commands;

use App\Http\Traits\UtilityTrait;
use App\Models\Estate;
use App\Models\InvoiceMaster;
use App\Models\Lead;
use App\Models\Property;
use App\Models\Receipt;
use App\Models\SalaryStructure;
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

        /*$structures = SalaryStructure::all();
        foreach($structures as $structure){
            $structure->slug = Str::slug($structure->ss_name).substr(sha1(time()),29,40);
            $structure->save();
        }*/
        /*$leads = Lead::all();
        foreach ($leads as $lead){
            $lead->entry_month = date('m', strtotime($lead->entry_date)) ??  now();
            $lead->entry_year = date('Y', strtotime($lead->entry_date)) ??  now();
            $lead->save();
        }*/
        //chart of account fix
        $codes = [
            '100000',
            '120000',
            '120010',
            '120011',
            '120012',
            '120013',
            '120100',
            '120101',
            '120102',
            '120103',
            '121000',
            '130000',
            '170000',
            '170010',
            '170011',
            '170012',
            '170013',
            '170014',
            '170015',
            '170016',
            '170017',
            '170018',
            '170019',
            '170020',
            '170021',
            '170022',
            '170023',
            '170024',
            '171000',
            '171001',
            '171002',
            '171003',
            '171004',
            '172000',
            '172001',
            '172002',
            '172003',
            '172004',
            '172006',
            '172008',
            '172005',
            '300000',
            '300100',
            '300120',
            '300140',
            '300160',
            '300180',
            '300200',
            '300220',
            '300240',
            '300260',
            '300280',
            '300300',
            '210000',
            '210010',
            '210011',
            '210012',
            '410000',
            '410100',
            '410101',
            '410102',
            '410103',
            '410104',
            '410105',
            '420000',
            '510000',
            '510010',
            '510011',
            '510012',
            '510013',
            '510014',
            '510015',
            '510016',
            '510017',
            '510018',
            '510019',
            '510020',
            '510021',
            '510022',
            '510023',
            '520000',
            '520010',
            '520011',
            '520012',
            '520013',
            '520014',
            '520015',
            '520016',
            '520017',
            '520018',
            '520019',
            '520021',
            '520022',
            '520023',
            '520024',
            '520025',
            '520026',
            '520027',
            '520028',
            '520029',
            '520030',
            '520031',
            '520032',
            '520033',
            '520034',
            '520035',
            '520036',
            '520037',
            '520038',
            '520039',
            '520040',
            '520041',
            '510024',
            '520042',
            '210013',
            '520043',
            '520044',
        ];
       /* $names = [
            assets
property, plant & equipment:
building
plant & machinery
motor vehicle
furniture & equipment
accumulated depreciation building
accummulated depreciation plant & machinery
accummulated depreciation motor vehicle
accummulated depreciation furniture & equipment
investment properties
current assets:
bank accounts:
fidelity bank plc
zenith bank plc
united bank for africa plc
                polaris bank
keystone bank
ecobank plc
wema bank plc
first bank of nigeria
first generation management account 1
first generation mortgage bank 1
first generation mortgage bank 3
first generation mortgage bank - smart home
first generation mortgage bank - rent-to-own
first generation mortgage bank - gratuity a/c.
    fixed deposit - with various banks
inventory:
stock of building material
stock of roofing material
stock of finishing accessories
building for sale
             trade receivables:
        trade receivables - sales
trade receivable - mortgage
sundry receivables - others
interest receivables - fixed deposit
interest receivables - mortgages
other receivables - wht unutilized
staff receivables - loans & advances
liabilities:
bank loan
deposit for houses - customers yet to complete payt
trade payables - vendors for material supply
                             trade payables - sub-contrators on project
trade payables - sub-contrators services
tax payables - company income tax
tax payables - education tax
witholding tax payables - deductions unremitted
value added tax payables
other payables
intercompany balances - loan among  group coy
capital and reserves:
share capital - issued
capital reserves
retained earning - profit
income:
direct sales - customers
mortgage sales - through fgmb
mdas sales
rent income - investment income
interest income
investment income
other income
direct costs - other income
property acquisition and compensation - land related
property acquisition and compensation - building related
materials consumed on construction
roads and drainages construction costs
planning and approval expenses
roofing expenses
haulage of material expenses
electricity installation expenses
itenerant labour costs
security expenses
machinery running expenses
refund expenses - customers
depreciation - plant & machinery expense
depreciation - motor vehicle- site veh.
    administrative expenses:
interest expenses - loans/overdraft
directors fees
bank charges - on current account
staff costs - payroll expenses
staff welfare - end of the year activities
staff uniforms & safety apparels
staff gratuity & pension expenses
training and development
motor vehicle running & maintenance
medical expenses
rates/business licences
power & utility
insurance expenses
professional fees - legal
professional fees - other consultant
audit and accountancy fees
stationery and printing expenses
repairs and maintenance buildings
communication expenses
postages, coureir expenses
internet and dstv subscription
subsistence and travels
medical expenses
marketing and advertising
sales commission expenses
business promotion/ relationship costs
meals & office & entertainments
office maintenance & cleaning items
office equipment repairs & maintenance
depreciation - building
depreciation - furniture & equipment
Cost of Sales
Income Tax
Capital
Petty Cash
Sales Discount
        ];*/
    }
}
