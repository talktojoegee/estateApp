<?php

namespace App\Imports;

use App\Models\BqOption;
use App\Models\BuildingType;
use App\Models\BulkCashbookImportDetail;
use App\Models\ConstructionStage;
use App\Models\Estate;
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
        //return dd($row);
        $constructionStage = ConstructionStage::getConstructionStageByName($row[18]);
        $estate = Estate::getEstateByName($row[1]);
        $buildingType = BuildingType::getBuildingTypeByName($row[2]);
        $bq = BqOption::getBQOptionByName($row[10]);
        $propertyTitle = PropertyTitle::getPropertyTitleByName($row[20]);
        $enSuite = 1;
        $randNum = rand(99,999);
        //return dd($estate);
        switch ($row[7]){
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
        $propertyCondition = 1;
        switch ($row[17]){
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
        'entry_date' => now(),
        'master_id' => $this->masterId,
        'estate_id' => !empty($estate) ? $estate->e_id : 1,
        'added_by' => Auth::user()->id,
        'property_title' => !empty($propertyTitle) ? $propertyTitle->pt_id : 1,
        'property_name' => $row[0] ?? 'Unknown_'.$randNum,
        'house_no' => $row[3] ?? null,
        'shop_no' => $row[4] ?? null,
        'plot_no' => $row[5] ?? null,
        'no_of_office_rooms' => $row[6] ?? null,
        'office_ensuite_toilet_bathroom' => $enSuite,
        'no_of_shops' => $row[8] ?? null,
        'building_type' => !empty($buildingType) ? $buildingType->bt_id : 1,
        'total_no_bedrooms' => $row[9] ?? 0,
        'with_bq' => !empty($bq) ? $bq->bqo_id : 1,
        'no_of_floors' => $row[11] ?? 0,
        'no_of_toilets' => $row[12] ?? 0,
        'no_of_car_parking' => $row[13] ?? 0,
        'no_of_units' => $row[14] ?? 0,
        'price' => !empty($row[15]) ? floatval(preg_replace('/[^\d.]/', '', $row[15])) : 0,
        'amount_paid' => !empty($row[16]) ? floatval(preg_replace('/[^\d.]/', '', $row[16])) : 0,
        'property_condition' => $propertyCondition,
        'construction_stage' => !empty($constructionStage) ? $constructionStage->cs_id : 1,
        'land_size' => $row[19] ?? null,
        'gl_id' => 1,
        'description' => $row[21] ?? null,
        'occupied_by'=>null,

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
}
