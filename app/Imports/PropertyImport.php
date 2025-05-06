<?php

namespace App\Imports;

use App\Models\BqOption;
use App\Models\BuildingType;
use App\Models\BulkCashbookImportDetail;
use App\Models\ConstructionStage;
use App\Models\Estate;
use App\Models\Lead;
use App\Models\PropertyBulkImportDetail;
use App\Models\PropertyTitle;
use App\Models\TransactionCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStartRow;

class PropertyImport implements ToModel, WithStartRow, WithMultipleSheets
{
    public $header, $masterId;

    public function __construct($header, $masterId)
    {
        $this->masterId = $masterId;
        $this->header = $header;
    }
    /*
     * 0 = PROPERTY SPECIFICATION
     * 1 = PROPERTY TYPE
     * 2 = PLOT NUMBER
     * 3 = HOUSE NUMBER
     * 4 = SHOP NUMBER
     * 5 = STREET NAME
     * 6 = BLOCK
     * 7 = ESTATE
     * 8 = LOCATION
     * 9 = AVAILABILITY
     * 10 = BANK DETAILS
     * 11 = ACCOUNT NUMBER
     * 12 = MODE OF PAYMENT
     * 13 = PROPERTY PRICE
     * 14 = AMOUNT PAID
     * 15 = BALANCE
     * 16 = PURCHASE STATUS
     * 17 = ALLOCATION LETTER
     * 18 = 2ND ALLOTEE
     * 19 = 3RD ALLOTEE
     * 20 = 4TH ALLOTEE
     * 21 = 5TH ALLOTEE
     * 22 = RENT AMOUNT
     * 23 = CUSTOMER ID
     * 24 = CUSTOMERS NAME
     * 25 = CUSTOMER'S
     * 26 = PHONE NO.
     * 27 = GENDER
     * 28 = OCCUPATION
     * 29 = CUSTOMER ADDRESS
     * 30 = CUSTOMER'S EMAIL
     */


    /**
     * @return int
     */
    public function startRow(): int
    {
        if(isset($this->header)){
            return 2;
        }
        return 1;

    }
    public function sheets(): array
    {
        return [
            0 => $this,
            //1 => new SecondSheetImport(),
        ];
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if (collect($row)->filter()->isEmpty()) {
            return null; // Skip row
        }
        $constructionStage = ConstructionStage::getConstructionStageByName($row[19]);
        $estate = Estate::getEstateByName($row[7]); //estate
        $buildingType = BuildingType::getBuildingTypeByName($row[1]); //property type
        $bq = BqOption::getBQOptionByName($row[11]);
        $propertyTitle = PropertyTitle::getPropertyTitleByName($row[21]);
        $enSuite = 1;
        $randNum = rand(99,999);
        //return dd($estate);
        switch ($row[8]){
            case 'None':
              $enSuite = 0;
              break;
            case 'Yes':
                $enSuite = 1;
                break;
            case 'No':
                $enSuite = 2;
                break;
        }
        $customerId = null;
        if(!is_null($row[23])){
            //extract first CS89384
            $val = substr($row[23],2);
            if(ctype_digit($val)){
                $lead = Lead::find($val);
                if(!empty($lead)){
                    $customerId = $lead->id;
                }
            }else{
               $record = $this->__saveNewLead($row[23]);
                $customerId = $record->id;
            }
        }
        $propertyCondition = 1;
        switch ($row[18]){
            case 'Good':
                $propertyCondition = 0;
              break;
            case 'Under Repair':
                $propertyCondition = 1;
                break;
            case 'Bad':
                $propertyCondition = 2;
                break;
            case 'Fair':
                $propertyCondition = 3;
                break;
        }
        return new PropertyBulkImportDetail([
            'property_name' => $row[0] ?? 'Unknown_'.$randNum,
            'building_type' => !empty($buildingType) ? $buildingType->bt_id : 1,
            'plot_no' => $row[2] ?? null, //plot number
            'house_no' => $row[3] ?? null, //house number
            'shop_no' => $row[4] ?? null, //shop number
            'street' => $row[5] ?? null, //street name
            'block' => $row[6] ?? null, //block
            'estate_id' => !empty($estate) ? $estate->e_id : 1, //estate
            'location' => $row[8] ?? '', //location
            'availability' => $row[9] ?? '', //availability
            'bank_details' => $row[10] ?? '', //bank details
            'account_number' => $row[11] ?? '', //account number
            'mode_of_payment' => $row[12] ?? '', //mode_of_payment
            'price' => !empty($row[13]) ? floatval(preg_replace('/[^\d.]/', '', $row[13])) : 0, //price
            'amount_paid' => !empty($row[14]) ? floatval(preg_replace('/[^\d.]/', '', $row[14])) : 0, //amount paid
            'balance' => !empty($row[15]) ? floatval(preg_replace('/[^\d.]/', '', $row[15])) : 0, //balance
            'purchase_status' => $row[16] ?? '', //purchase_status
            'provisional_letter' => $row[17] ?? '', //provisional letter
            'allocation_letter' => $row[18] ?? '', //allocation letter
            'second_allotee' => $row[19] ?? '', //2ND ALLOTEE
            'third_allotee' => $row[20] ?? '', //3RD ALLOTEE
            'fourth_allotee' => $row[21] ?? '', //4th ALLOTEE
            'fifth_allotee' => $row[22] ?? '', //5th ALLOTEE
            'rent_amount' => $row[23] ?? '', //rent_amount
            'customer_id' => $row[24] ?? '', //customer_id
            'customer_name' => $row[25] ?? '', //customer_name
            'customer_phone' => $row[26] ?? '', //customer_phone
            'customer_gender' => $row[27] ?? '', //customer_gender
            'occupation' => $row[28] ?? '', //occupation
            'customer_address' => $row[29] ?? '', //customer_address
            'customer_email' => $row[30] ?? '', //customer_email

            'no_of_office_rooms' => 0,
            'entry_date' => now(),
            'master_id' => $this->masterId,
            'added_by' => Auth::user()->id,
            'property_title' => !empty($propertyTitle) ? $propertyTitle->pt_id : 1,
            'office_ensuite_toilet_bathroom' => $enSuite,
            'no_of_shops' =>  null,
            'total_no_bedrooms' =>  0,
            'with_bq' => 1,
            'no_of_floors' =>  0,
            'no_of_toilets' =>  0,
            'no_of_car_parking' =>  0,
            'no_of_units' =>  0,



        'property_condition' => $propertyCondition,
        'construction_stage' => !empty($constructionStage) ? $constructionStage->cs_id : 1,
        'land_size' =>  null,
        'gl_id' => 1,
        'description' => $row[0] ?? null, //prop. specification
        'customer' => $row[23] ?? null,
        'occupied_by'=>$customerId, //sold to this customer

        'kitchen' => 1,
        'borehole' => 1,
        'pool' => 1,
        'security' => 1,
        'car_park' => 1,
        'garage' => 1,
        'laundry' => 1,
        'store_room' => 1,
        'balcony' => 1,
        'elevator' => 1,
        'play_ground' => 1,
        'lounge' => 1,

        'wifi' => 1,
        'tv' => 1,
        'dryer' => 1,
        'c_oxide_alarm' => 1,
        'air_conditioning' => 1,
        'washer' => 1,
        'bq' => 1,
        'penthouse' => 1,
        'gate_house' => 1,
        'gen_house' => 1,
        'fitted_wardrobe' => 1,
        'guest_toilet' => 1,
        'anteroom' => 1,
        'slug' => Str::slug($row[0] ?? 'Unknown_'.$randNum).'-'.substr(sha1(time()),32,40),
        ]);
    }



    private function __saveNewLead($name){
        $lead = new Lead();
        $lead->entry_date =  now();
        $lead->added_by = Auth::user()->id;
        $lead->org_id = Auth::user()->org_id;
        $lead->first_name = $name ?? '';
        $lead->last_name = $request->lastName ?? '';
        $lead->middle_name = null;
        $lead->email = 'placeholder@gmail.com';
        $lead->phone = '+234';
        $lead->dob = null;
        $lead->source_id = null;
        $lead->status = null;
        $lead->gender = 1;
        $lead->street =  null;
        $lead->city = null;
        $lead->state = null;
        $lead->code = null;
        $lead->occupation = null;
        $lead->entry_month = date('m',strtotime(now()));
        $lead->entry_year = date('Y',strtotime(now()));
        $lead->slug = Str::slug($name).'-'.Str::random(8);
        //Next of kin
        $lead->next_full_name = null;
        $lead->next_primary_phone = null;
        $lead->next_alt_phone = null;
        $lead->next_email = null;
        $lead->next_relationship = null;
        $lead->save();
        return $lead;
    }
}
