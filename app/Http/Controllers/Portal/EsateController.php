<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Estate;
use App\Models\EstateAmenity;
use App\Models\EstateAmenityValue;
use App\Models\State;
use Illuminate\Http\Request;

class EsateController extends Controller
{

    public function __construct()
    {
        $this->estate = new Estate();
        $this->estateamenity = new EstateAmenity();
        $this->state = new State();
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
                    'amenities'=>'required|array',
                    'amenities.*'=>'required'
                ],[
                    "name.required"=>"Enter estate name",
                    "address.required"=>"Where is this estate located?",
                    "state.required"=>"In which state is it located?",
                    "city.required"=>"Enter city name",
                    "amenities.required"=>"At least one estate amenity is required",
                    "amenities.*"=>"At least one estate amenity is required",
                    "amenities.array"=>"At least one estate amenity is required",
                ]);
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


    public function showAddNewEstateForm(){
        return view('estate.create');
    }
}
