<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Estate;
use App\Models\EstateAmenity;
use App\Models\EstateAmenityValue;
use App\Models\Property;
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
    }

    public function showEstates(Request $request){
        $method = $request->method();
        switch ($method){
            case 'GET':
                return view('estate.index',[
                    'estates'=>$this->estate->getAllEstates(),
                    'amenities'=>$this->estateamenity->getAllEstateAmenities(),
                    'states'=>$this->state->getStatesByCountryId(161)
                ]);
            case 'POST':
                $this->validate($request,[
                    'name'=>'required',
                    'address'=>'required',
                    'state'=>'required',
                    'city'=>'required',
                    'referenceCode'=>'required',
                    'amenities'=>'required|array',
                    'amenities.*'=>'required'
                ],[
                    "name.required"=>"Enter estate name",
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
                //register amenity values
                foreach($request->amenities as $key=> $amenity){
                    //$val = isset($request->amenity[$key]) ? 1 : 0;
                    EstateAmenityValue::create([
                        'eav_estate_id'=>$estate->e_id,
                        'eav_value'=>null,
                        'eav_amenity_id'=>$amenity
                    ]);
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
            return dd(array_unique($customerIds));
            return view('estate.view',[
                'record'=>$estate,
                'receipts'=>$this->receipt->getReceiptsByPropertyIds($propertyIds),
                ]);
        }else{
            session()->flash("error", "Whoops! No record found.");
            return back();
        }
    }


    public function showAddNewEstateForm(){
        return view('estate.create');
    }
}
