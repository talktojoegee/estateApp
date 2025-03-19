<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyBulkImportDetail extends Model
{
    use HasFactory;
    protected $fillable =
        [
        'master_id',
        'entry_date',
        'estate_id',
        'added_by',
        'occupied_by',
        'property_title',
        'street',
        'property_name',
        'house_no',
        'shop_no',
        'plot_no',
        'no_of_office_rooms',
        'office_ensuite_toilet_bathroom',
        'no_of_shops',
        'building_type',
        'total_no_bedrooms',
        'with_bq',
        'no_of_floors',
        'no_of_toilets',
        'no_of_car_parking',
        'no_of_units',
        'frequency',
        'currency_id',
        'price',
        'payment_status',
        'amount_paid',
        'property_condition',
        'construction_stage',
        'land_size',
        'gl_id',
        'sold_to',
        'date_sold',
        'location_id',
        'address',
        'built_on',
        'description',
        'status',
        'bedrooms',
        'living_rooms',
        'kitchen',
        'borehole',
        'pool',
        'security',
        'car_park',
        'garage',
        'laundry',
        'store_room',
        'balcony',
        'elevator',
        'play_ground',
        'lounge',
        'guest_toilet',
        'anteroom',
        'fitted_wardrobe',
        'bq',
        'penthouse',
        'gate_house',
        'gen_house',
        'slug',
        'customer',
        'block',
        'location',
        'availability',
        'bank_details',
        'account_number',
        'mode_of_payment',
        'balance',
        'purchase_status',
        'allocation_letter',
        'second_allotee',
        'third_allotee',
        'fourth_allotee',
        'fifth_allotee',
        'rent_amount',
        'customer_id',
        'customer_name',
        'customer_phone',
        'customer_gender',
        'occupation',
        'customer_address',
        'customer_email',
    ];

    public function getPropertyType(){
        return $this->belongsTo(BuildingType::class, 'building_type');
    }

    public function getAddedBy(){
        return $this->belongsTo(User::class, 'added_by');
    }

    public function getBuildingType(){
        return $this->belongsTo(BuildingType::class, 'building_type');
    }
    public function getPropertyTitle(){
        return $this->belongsTo(PropertyTitle::class, 'property_title');
    }
    public function getWithBQOption(){
        return $this->belongsTo(BqOption::class, 'with_bq');
    }
    public function getConstructionStage(){
        return $this->belongsTo(ConstructionStage::class, 'construction_stage');
    }

    public function getEstate(){
        return $this->belongsTo(Estate::class, 'estate_id');
    }

    public function getGlAccount(){
        return $this->belongsTo(ChartOfAccount::class, 'gl_id', 'glcode');
    }

    public function getLocation(){
        return $this->belongsTo(State::class, 'location_id');
    }
    public function getManager(){
        return $this->belongsTo(User::class, 'manager_id');
    }
    public function getRealtor(){
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }
    public function getOwner(){
        return $this->belongsTo(User::class, 'owner_id');
    }
    public function getOccupant(){
        return $this->belongsTo(User::class, 'occupied_by');
    }

    public function getOccupiedBy(){
        return $this->belongsTo(Lead::class, 'occupied_by');
    }

    public function getEntryById($id){
        return PropertyBulkImportDetail::find($id);
    }

    public function getPropertyDetailById($id){
        return PropertyBulkImportDetail::find($id);
    }
    public function getPropertyDetailByMasterId($masterId){
        return PropertyBulkImportDetail::where('master_id',$masterId)->get();
    }




}
