<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Property extends Model
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
        ];

    public function addProperty(Request $request){
        $property = new Property();
        $property->estate_id = $request->estate;
        $property->added_by = Auth::user()->id;
        $property->property_title = $request->propertyTitle ?? '';
        $property->property_name = $request->propertyName ?? '';
        $property->house_no = $request->houseNo ?? null;
        $property->shop_no = $request->shopNo ?? null;
        $property->plot_no = $request->plotNo ?? null;
        $property->no_of_office_rooms = $request->noOfOfficeRooms ?? null;
        $property->office_ensuite_toilet_bathroom = $request->officeShopEnsuite ?? 1;
        $property->no_of_shops = $request->noOfShops ?? null;
        $property->building_type = $request->buildingType ?? '';
        $property->total_no_bedrooms = $request->totalBedrooms ?? 0;
        $property->with_bq = $request->withBQ ?? 0;
        $property->no_of_floors = $request->noOfFloors ?? 0;
        $property->no_of_toilets = $request->noOfToilets ?? 0;
        $property->no_of_car_parking = $request->noOfCarParking ?? 0;
        $property->no_of_units = $request->noOfUnits ?? 0;
        $property->price = $request->price ?? 0;
        $property->property_condition = $request->propertyCondition ?? null;
        $property->construction_stage = $request->constructionStage ?? null;
        $property->land_size = $request->landSize ?? null;
        $property->gl_id = $request->account ?? null;
        $property->description = $request->propertyDescription ?? null;

        $property->kitchen = isset($request->kitchen) ? 1 : 0;
        $property->borehole = isset($request->borehole) ? 1 : 0;
        $property->pool = isset($request->pool) ? 1 : 0;
        $property->security = isset($request->security) ? 1 : 0;
        $property->car_park = isset($request->carPark) ? 1 : 0;
        $property->garage = isset($request->garage) ? 1 : 0;
        $property->laundry = isset($request->laundry) ? 1 : 0;
        $property->store_room = isset($request->storeRoom) ? 1 : 0;
        $property->balcony = isset($request->balcony) ? 1 : 0;
        $property->elevator = isset($request->elevator) ? 1 : 0;
        $property->play_ground = isset($request->playGround) ? 1 : 0;
        $property->lounge = isset($request->lounge) ? 1 : 0;

        $property->wifi = isset($request->wifi) ? 1 : 0;
        $property->tv = isset($request->tv) ? 1 : 0;
        $property->dryer = isset($request->dryer) ? 1 : 0;
        $property->c_oxide_alarm = isset($request->smokeAlarm) ? 1 : 0;
        $property->air_conditioning = isset($request->airConditioning) ? 1 : 0;
        $property->washer = isset($request->washer) ? 1 : 0;
        $property->bq = isset($request->bq) ? 1 : 0;
        $property->penthouse = isset($request->penthouse) ? 1 : 0;
        $property->gate_house = isset($request->gate_house) ? 1 : 0;
        $property->gen_house = isset($request->gen_house) ? 1 : 0;
        $property->fitted_wardrobe = isset($request->fitted_wardrobe) ? 1 : 0;
        $property->guest_toilet = isset($request->guest_toilet) ? 1 : 0;
        $property->anteroom = isset($request->anteroom) ? 1 : 0;
        $property->slug = Str::slug($request->propertyName).'-'.substr(sha1(time()),32,40);
        $property->save();
        return $property;
    }
    public function editProperty(Request $request){
        $property =  Property::find($request->propertyId);
        $property->estate_id = $request->estate;
        $property->added_by = Auth::user()->id;
        $property->property_title = $request->propertyTitle ?? '';
        $property->property_name = $request->propertyName ?? '';
        $property->house_no = $request->houseNo ?? null;
        $property->shop_no = $request->shopNo ?? null;
        $property->plot_no = $request->plotNo ?? null;
        $property->no_of_office_rooms = $request->noOfOfficeRooms ?? null;
        $property->office_ensuite_toilet_bathroom = $request->officeShopEnsuite ?? 1;
        $property->no_of_shops = $request->noOfShops ?? null;
        $property->building_type = $request->buildingType ?? '';
        $property->total_no_bedrooms = $request->totalBedrooms ?? 0;
        $property->with_bq = $request->withBQ ?? 0;
        $property->no_of_floors = $request->noOfFloors ?? 0;
        $property->no_of_toilets = $request->noOfToilets ?? 0;
        $property->no_of_car_parking = $request->noOfCarParking ?? 0;
        $property->no_of_units = $request->noOfUnits ?? 0;
        $property->price = $request->price ?? 0;
        $property->property_condition = $request->propertyCondition ?? null;
        $property->construction_stage = $request->constructionStage ?? null;
        $property->land_size = $request->landSize ?? null;
        $property->gl_id = $request->account ?? null;
        $property->description = $request->propertyDescription ?? null;

        $property->kitchen = isset($request->kitchen) ? 1 : 0;
        $property->borehole = isset($request->borehole) ? 1 : 0;
        $property->pool = isset($request->pool) ? 1 : 0;
        $property->security = isset($request->security) ? 1 : 0;
        $property->car_park = isset($request->carPark) ? 1 : 0;
        $property->garage = isset($request->garage) ? 1 : 0;
        $property->laundry = isset($request->laundry) ? 1 : 0;
        $property->store_room = isset($request->storeRoom) ? 1 : 0;
        $property->balcony = isset($request->balcony) ? 1 : 0;
        $property->elevator = isset($request->elevator) ? 1 : 0;
        $property->play_ground = isset($request->playGround) ? 1 : 0;
        $property->lounge = isset($request->lounge) ? 1 : 0;

        $property->wifi = isset($request->wifi) ? 1 : 0;
        $property->tv = isset($request->tv) ? 1 : 0;
        $property->dryer = isset($request->dryer) ? 1 : 0;
        $property->c_oxide_alarm = isset($request->smokeAlarm) ? 1 : 0;
        $property->air_conditioning = isset($request->airConditioning) ? 1 : 0;
        $property->washer = isset($request->washer) ? 1 : 0;
        $property->bq = isset($request->bq) ? 1 : 0;
        $property->penthouse = isset($request->penthouse) ? 1 : 0;
        $property->gate_house = isset($request->gate_house) ? 1 : 0;
        $property->gen_house = isset($request->gen_house) ? 1 : 0;
        $property->fitted_wardrobe = isset($request->fitted_wardrobe) ? 1 : 0;
        $property->guest_toilet = isset($request->guest_toilet) ? 1 : 0;
        $property->anteroom = isset($request->anteroom) ? 1 : 0;
        //$property->slug = Str::slug($request->propertyName).'-'.substr(sha1(time()),32,40);
        $property->save();
        return $property;
    }



    public function getPropertyType(){
        return $this->belongsTo(BuildingType::class, 'building_type');
    }

    public function getAllocations(){
        return $this->hasMany(PropertyAllocation::class, 'property_id');
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

    public function getSoldTo(){
        return $this->belongsTo(Lead::class, 'sold_to');
    }

    public function getPropertyGalleryImages(){
        return $this->hasMany(PropertyGallery::class, 'property_id');
    }



    public function getPropertiesByStatus($status){
        return Property::where('status', $status)->orderBy('id', 'DESC')->get();
    }

    public function getPropertyBySlug($slug){
        return Property::where('slug', $slug)->first();
    }

    public function getPropertyById($propertyId){
        return Property::find($propertyId);
    }

    public function editManagedBy(Request $request){
        $prop =  Property::find($request->propertyId);
        $prop->manager_id = $request->managedBy;
        $prop->save();
    }
    public function editOwnedBy(Request $request){
        $prop =  Property::find($request->propertyId);
        $prop->owner_id = $request->ownedBy;
        $prop->save();
    }
    public function editOccupancyStatus(Request $request){
        $prop =  Property::find($request->propertyId);
        $prop->status = $request->occupancyStatus;
        $prop->save();
    }

    public function updatePropertyStatus($propertyId, $status, $userId){
        $prop =  Property::find($propertyId);
        $prop->status = $status;
        $prop->occupied_by = $userId;
        $prop->save();
    }

    public function getGalleryFeaturedImageByPropertyId($propertyId){
        return PropertyGallery::where('property_id', $propertyId)->first();
    }
    public function searchProperty($searchTerm, $location){
        return Property::where('property_name', 'LIKE', "%{$searchTerm}%")
            ->orWhere('location_id', $location)
            ->orderBy('id', 'DESC')->get();
    }

    public function searchPropertyByPluckingIds($searchTerm, $location){
        return Property::where('property_name', 'LIKE', "%{$searchTerm}%")
            ->orWhere('location_id', $location)
            ->orderBy('id', 'DESC')->pluck('id');
    }

    public function pluckPropertiesByLocation($locationId){
        return Property::where('location_id', $locationId)->pluck('id');
    }

    public function getPropertyOccupiedByUser($userId){
        return Property::where('occupied_by', $userId)->first();
    }

    public function getAllTenantPropertiesByLocationIds($locationIds){
        return Property::whereIn('location_id', $locationIds)->orderBy('id', 'DESC')->get();
    }
    public function getPropertyList($propertyIds){
        return Property::whereIn('id', $propertyIds)->orderBy('id', 'DESC')->get();
    }

   public function getPropertiesByEstateId($estateId){
        return Property::where('estate_id', $estateId)
            ->orderBy('id', 'DESC')->take(5)->get();
    }
   public function getAvailablePropertiesByEstateId($estateId){
        return Property::where('estate_id', $estateId)->where('status',0)
            ->orderBy('id', 'DESC')->take(5)->get();
    }

    public function getAllProperties($status){
        return Property::whereIn('status', $status)->orderBy('id', 'DESC')->get();
    }

    public function getTopSellingLocationsWithin($start, $end, $counter){
        return Property::select('estate_id',
            DB::raw('SUM( total) as amount'),
            DB::raw('COUNT( estate_id) as estate_count'),
            'estates.e_name', 'estates.e_slug'
        )
            ->join('receipts', 'properties.id', '=', 'receipts.property_id')
            ->join('estates', 'estates.e_id', '=', 'properties.estate_id')
            ->whereBetween('receipts.payment_date', [$start, $end])
            ->groupBy('estate_id')
            ->orderBy('estate_count', 'desc')
            ->take($counter)
            ->get();
    }
    public function getUnderperformingLocationsWithin($start, $end, $counter){
        return Estate::select(
            'estates.e_id',
            'estates.e_name',
            'estates.e_slug',
            DB::raw('COUNT(properties.id) as estate_count'),
            DB::raw('IFNULL(SUM(receipts.total), 0) as amount')
        )
            ->leftJoin('properties', 'estates.e_id', '=', 'properties.estate_id')
            ->leftJoin('receipts', function($join) use ($start, $end) {
                $join->on('properties.id', '=', 'receipts.property_id')
                    ->whereBetween('receipts.payment_date', [$start, $end]);  // Filter receipts by date range
            })
            ->groupBy('estates.e_id', 'estates.e_name', 'estates.e_slug')
            ->orderBy('amount', 'asc')  // Order by total sales amount (underperforming)
            ->take($counter)
            ->get();
    }
}
