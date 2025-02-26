<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Estate;
use App\Models\EstateAmenity;
use App\Models\EstateAmenityValue;
use App\Models\Lead;
use App\Models\Property;
use App\Models\PropertyAllocation;
use App\Models\Receipt;
use App\Models\State;
use Illuminate\Http\Request;

class EsateController extends Controller
{

    public function __construct()
    {
        $this->estate = new Estate();
        $this->estateamenity = new EstateAmenity();
        $this->state = new State();
        $this->property = new Property();
        $this->receipt = new Receipt();
        $this->propertyallocation = new PropertyAllocation();
        $this->lead = new Lead();
        $this->country = new Country();
    }

    public function showEstates(Request $request){
        $method = $request->method();
        switch ($method){
            case 'GET':
                return view('estate.index',[
                    'estates'=>$this->estate->getAllEstates(),
                    'amenities'=>$this->estateamenity->getAllEstateAmenities(),
                    'states'=>$this->state->getStatesByCountryId(161),
                    'countries'=>$this->country->getCountries()
                ]);
            case 'POST':
                $this->validate($request,[
                    'name'=>'required',
                    'address'=>'required',
                    'country'=>'required',
                    'city'=>'required',
                    'referenceCode'=>'required',
                    'amenities'=>'required|array',
                    'amenities.*'=>'required'
                ],
                    [
                        "name.required"=>"Enter estate name",
                        "country.required"=>"Choose country from the list",
                        "address.required"=>"Where is this estate located?",
                        "state.required"=>"In which state is it located?",
                        "city.required"=>"Enter city name",
                        "referenceCode.required"=>"Enter a unique reference code",
                        "amenities.required"=>"At least one estate amenity is required",
                        "amenities.*"=>"At least one estate amenity is required",
                        "amenities.array"=>"At least one estate amenity is required",
                    ]);
                $code = Estate::getEstateByRefCode($request->referenceCode);
                if(!empty($code)){
                    session()->flash("error", "Whoops! This estate reference code is already in use.");
                    return back();
                }

                $estate = $this->estate->addNewEstate($request);
                if(empty($estate)){
                    session()->flash("error", "Whoops! Something went wrong.");
                    return back();
                }
                foreach($request->amenities as $key=> $amenity){
                    EstateAmenityValue::create([
                        'eav_estate_id'=>$estate->e_id,
                        'eav_value'=>null,
                        'eav_amenity_id'=>$amenity
                    ]);
                }
                session()->flash("success", "Action successful!");
                return back();
            case 'PUT':
                $this->validate($request,
                    [
                        'estate'=>'required',
                        'name'=>'required',
                        'address'=>'required',
                        //'country'=>'required',
                        'city'=>'required',
                        'referenceCode'=>'required',
                        'amenities'=>'required|array',
                        'amenities.*'=>'required'
                    ],
                    [
                        "name.required"=>"Enter estate name",
                        "country.required"=>"Choose country from the list",
                        "address.required"=>"Where is this estate located?",
                        "state.required"=>"In which state is it located?",
                        "city.required"=>"Enter city name",
                        "referenceCode.required"=>"Enter a unique reference code",
                        "amenities.required"=>"At least one estate amenity is required",
                        "amenities.*"=>"At least one estate amenity is required",
                        "amenities.array"=>"At least one estate amenity is required",
                    ]);

                /* $code = Estate::getEstateByRefCode($request->referenceCode);
                 if(!empty($code)){
                     session()->flash("error", "Whoops! This estate reference code is already in use.");
                     return back();
                 }*/

                $estate = Estate::getEstateById($request->estate);
                if(empty($estate)){
                    session()->flash("error", "Whoops! No record found");
                    return back();
                }
                $estate = $this->estate->updateEstate($request);
                if(empty($estate)){
                    session()->flash("error", "Whoops! Something went wrong.");
                    return back();
                }
                $amenities = EstateAmenityValue::where('eav_estate_id', $estate->e_id)->get();
                if(!empty($amenities)){
                    foreach($amenities as $ameni){
                        $ameni->delete();
                    }
                    foreach($request->amenities as $key=> $amenity){
                        EstateAmenityValue::create([
                            'eav_estate_id'=>$estate->e_id,
                            'eav_value'=>null,
                            'eav_amenity_id'=>$amenity
                        ]);
                    }
                }

                session()->flash("success", "Action successful!");
                return back();

        }

    }

    public function showEstateView($slug){
        $estate = $this->estate->getEstateBySlug($slug);
        if(!empty($estate)){
            //get IDs of properties in this estate
            $propertyIds = $this->property->getPropertiesByEstateId($estate->e_id)->pluck('id')->toArray();
            $customerIds = $this->property->getPropertiesByEstateId($estate->e_id)->pluck('occupied_by')->toArray();
            $customerIdsInAllocation = $this->propertyallocation->getAllocationByPropertyId($propertyIds, [1])
                ->pluck('customer_id')->toArray();
            $mergedCustomerIds = array_merge($customerIds, $customerIdsInAllocation);
            $uniqueCustomerIds = array_unique($mergedCustomerIds);
            return view('estate.view',[
                'record'=>$estate,
                'receipts'=>$this->receipt->getReceiptsByPropertyIds($propertyIds),
                'customers'=>$this->lead->getCustomerListByIds($uniqueCustomerIds),
                'countries'=>$this->country->getCountries(),
                'states'=>$this->state->getStatesByCountryId(161),
                'amenities'=>$this->estateamenity->getAllEstateAmenities(),
            ]);
        }else{
            session()->flash("error", "Whoops! No record found.");
            return back();
        }
    }


    public function EstateById(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request,[
            "id"=>"required"
        ]);
        $property = $this->property->getPropertyById($request->id);// Estate::getEstateById();
        $estate = Estate::getEstateById($property->estate_id) ?? [];
        return response()->json([
            'estate'=>$estate,
            //'price'=>$property->price,
            'paymentPlanName'=>$property->getPaymentPlan->pp_name ?? '',
            'paymentPlanDesc'=>$property->getPaymentPlan->pp_description ?? '',
        ],200);
    }


    public function showAddNewEstateForm(){
        return view('estate.create');
    }
}
