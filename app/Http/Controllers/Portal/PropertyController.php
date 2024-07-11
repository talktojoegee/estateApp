<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\BqOption;
use App\Models\BuildingType;
use App\Models\ChartOfAccount;
use App\Models\ConstructionStage;
use App\Models\Estate;
use App\Models\Property;
use App\Models\PropertyGallery;
use App\Models\PropertyTitle;
use App\Models\State;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function __construct()
    {
        $this->state = new State();
        $this->property = new Property();
        $this->estate = new Estate();
        $this->propertygallery = new PropertyGallery();
        $this->chartofaccount = new ChartOfAccount();

    }

    public function showAddProperty(Request $request){
        switch ($request->method()){
            case 'GET':
                return view('property.add-new-property',[
                    'states'=>$this->state->getStatesByCountryId(161),
                    'estates'=>$this->estate->getAllEstates(),
                    'accounts'=>$this->chartofaccount->getAllChartOfAccountsByType(1),
                    'buildingTypes'=>BuildingType::getBuildingTypes(),
                    'bqOptions'=>BqOption::getBQOptions(),
                    'constructionStages'=>ConstructionStage::getConstructionStages(),
                    'titles'=>PropertyTitle::getPropertyTitles()
                ]);
            case 'POST':
                $this->validatePropertySubmission($request);
                $property = $this->property->addProperty($request);
                $this->propertygallery->uploadPropertyGalleryImages($request, $property->id);
                session()->flash("success", "Action successful.");
                return back();
            default:
                abort(404);
        }
    }

    public function showManagePropertiesView(){
        return view('property.index',[
            'properties'=>$this->property->getAllProperties()
        ]);
    }

    private function validatePropertySubmission(Request $request){
        $this->validate($request,[
            'estate'=>'required',
            'propertyTitle'=>'required',
            'propertyName'=>'required',
            'buildingType'=>'required',
            'withBQ'=>'required',
            'propertyCondition'=>'required',
            'constructionStage'=>'required',
            'account'=>'required',
            'propertyDescription'=>'required',
            'price'=>'required', //this too should be used for listing
            'gallery'=>'required|array',
            'gallery.*'=>'required|image|mimes:jpeg,png,jpg',
        ],
            [
            "estate.required"=>"Select the estate to which this property belongs to",
            "propertyTitle.required"=>"Select property title",
            "buildingType.required"=>"What kind of building is this?",
            "withBQ.required"=>"Does this property has BQ? Choose the option that best describes it.",
            "propertyCondition.required"=>"What's the condition of this property?",
            "constructionStage.required"=>"At what stage of construction are you?",
            "account.required"=>"Choose the ledger account that should be used for this property.",
            "propertyDescription.required"=>"Give us brief information about this property.",
            "price.required"=>"Certainly this is not intended to be sold for FREE. Enter the price.",
            "propertyName.required"=>"What name would you give to this property?",
            "gallery.required"=>"Upload at least one image",
            "gallery.*"=>"Upload at least one image",
        ]);
    }


    public function showPropertyDetails($slug){
        $property = $this->property->getPropertyBySlug($slug);
        if(empty($property)){
            abort(404);
        }
        return view('property.view-property',[
            'property'=>$property,
            'states'=>$this->state->getStatesByCountryId(161),
            'estates'=>$this->estate->getAllEstates(),
            'accounts'=>$this->chartofaccount->getAllChartOfAccountsByType(1),
            'buildingTypes'=>BuildingType::getBuildingTypes(),
            'bqOptions'=>BqOption::getBQOptions(),
            'constructionStages'=>ConstructionStage::getConstructionStages(),
            'titles'=>PropertyTitle::getPropertyTitles()
        ]);
    }

    public function deletePropertyImage(Request $request){
        $this->validate($request,[
            'propertyId'=>'required',
            'imageId'=>'required'
        ]);
        $property = $this->property->getPropertyById($request->propertyId);
        if(!empty($property)){
            $response = $this->propertygallery->deleteImage($request->imageId, $request->propertyId);
            if($response){
                session()->flash("success", "Image deleted!");
                return redirect()->route('show-property-details', $property->slug);
            }else{
                session()->flash("error", "Could not delete image");
                return redirect()->route('show-property-details', $property->slug);
            }
        }else{
            session()->flash("success", "Record not found.");
            return back();
        }
    }

    public function updatePropertyDetails(Request $request){
        $this->validatePropertySubmission($request);
        $property = $this->property->editProperty($request);
        $this->propertygallery->uploadPropertyGalleryImages($request, $property->id);
        session()->flash("success", "Your changes were saved!");
        return back();
    }

}
