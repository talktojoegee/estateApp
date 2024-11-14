<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Traits\UtilityTrait;
use App\Imports\PropertyImport;
use App\Models\ActivityLog;
use App\Models\BqOption;
use App\Models\BuildingType;
use App\Models\ChartOfAccount;
use App\Models\ConstructionStage;
use App\Models\Estate;
use App\Models\FileModel;
use App\Models\InvoiceMaster;
use App\Models\Lead;
use App\Models\PaymentPlan;
use App\Models\Property;
use App\Models\PropertyAllocation;
use App\Models\PropertyBulkImportDetail;
use App\Models\PropertyBulkImportMaster;
use App\Models\PropertyGallery;
use App\Models\PropertyReservation;
use App\Models\PropertyTitle;
use App\Models\Receipt;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Excel;
class PropertyController extends Controller
{
    use UtilityTrait;

    public function __construct()
    {
        $this->state = new State();
        $this->property = new Property();
        $this->estate = new Estate();
        $this->propertygallery = new PropertyGallery();
        $this->chartofaccount = new ChartOfAccount();
        $this->propertyimportmaster = new PropertyBulkImportMaster();
        $this->propertyimportdetail = new PropertyBulkImportDetail();
        $this->invoicemaster = new InvoiceMaster();
        $this->lead = new Lead();
        $this->receipt = new Receipt();
        $this->propertyallocation = new PropertyAllocation();
        $this->propertyreservation = new PropertyReservation();
        $this->file = new FileModel();

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
                    'titles'=>PropertyTitle::getPropertyTitles(),
                    'paymentPlans'=>PaymentPlan::getPaymentPlans()
                ]);
            case 'POST':
                $this->validatePropertySubmission($request);
                $property = $this->property->addProperty($request);
                $estate = Estate::getEstateById($request->estate);
                if(!empty($estate)){
                    $code = $estate->e_ref_code.$this->padNumber($property->id,6);
                    $property->property_code = $code;
                    $property->save();
                }

                $this->propertygallery->uploadPropertyGalleryImages($request, $property->id);
                session()->flash("success", "Action successful.");
                return back();
            default:
                abort(404);
        }
    }
    public function propertyReservation(Request $request){
        switch ($request->method()){
            case 'GET':
                return view('property.new-reservation',[
                    'estates'=>$this->estate->getAllEstates(),
                    'customers'=>$this->lead->getAllOrgLeads()
                ]);
            case 'POST':
                $this->validate($request,[
                   "propertyCode"=>"required",
                    "lead"=>"required"
                ],[
                    "propertyCode.required"=>"Enter property code",
                    "lead.required"=>"Select customer from the list provided",
                ]);
                $property = $this->property->getPropertyByPropertyCode($request->propertyCode);
                if(empty($property)){
                    session()->flash("error", "Whoops! No record found.");
                    return back();
                }
                if($property->status != 0){
                    session()->flash("error", "Whoops! This property is not available for reservation.");
                    return back();
                }
                $filename = null;
                if ($request->hasFile('attachment')) {
                    $attachment = $this->file->uploadSingleFile($request);
                    $filename = $attachment->filename;
                }
                $reservation = $this->propertyreservation->addReservation($request, $property->id);
                $reservation->file = $filename;
                $reservation->save();
                session()->flash("success", "Action successful.");
                return back();
            default:
                abort(404);
        }
    }

    public function managePropertyReservation(Request $request){
        switch ($request->method()){
            case 'GET':
                return view('property.manage-reservation-requests',[
                    'requests'=>$this->propertyreservation->getAllReservationRequests(),

                ]);
            case 'POST':
                $this->validate($request,[
                   "propertyCode"=>"required",
                    "lead"=>"required"
                ],[
                    "propertyCode.required"=>"Enter property code",
                    "lead.required"=>"Select customer from the list provided",
                ]);
                $property = $this->property->getPropertyByPropertyCode($request->propertyCode);
                if(empty($property)){
                    session()->flash("error", "Whoops! No record found.");
                    return back();
                }
                if($property->status != 0){
                    session()->flash("error", "Whoops! This property is not available for reservation.");
                    return back();
                }
                $filename = null;
                if ($request->hasFile('attachment')) {
                    $attachment = $this->file->uploadSingleFile($request);
                    $filename = $attachment->filename;
                }
                $reservation = $this->propertyreservation->addReservation($request, $property->id);
                $reservation->file = $filename;
                $reservation->save();
                session()->flash("success", "Action successful.");
                return back();
            default:
                abort(404);
        }
    }

    public function actionReservation($type, $id){
        $authUser = Auth::user();
        $reservation = PropertyReservation::find($id);
        if(empty($reservation)){
            session()->flash("error", "No record found.");
            return back();
        }
        $action = $type ?? null;
        if(is_null($action)){
            session()->flash("error", "Something went wrong.");
            return back();
        }
        $intAction = $type == 'approve' ? 1 : 2;
        $reservation->status = $intAction;
        $reservation->date_actioned = now();
        $reservation->actioned_by = $authUser->id;
        $reservation->save();
        $property = $this->property->getPropertyById($reservation->property_id);
        if($action == 'approve'){
            if(!empty($property)){
                $property->status = 3; //reserved
                $property->save();
            }
        }

        $act = $action == 'approve' ? 'approved' : 'declined';
        $log = $authUser->first_name." ".$authUser->last_name." ".$act." property reservation request for(".$property->property_code.")";
        ActivityLog::registerActivity($authUser->org_id, null, $authUser->id, null, 'Property Reservation Request', $log);
        session()->flash("success", "Action successful.");
        return back();
    }

    public function getProperty(Request $request){
        $this->validate($request,[
            'propertyCode'=>"required"
        ]);
        $property = $this->property->getPropertyByPropertyCode($request->propertyCode);
        return view("property.partial._property-preview",
            ["property"=>$property]);
    }

    public function showManagePropertiesView($type){
        $properties = [];
        $title = null;
        switch ($type){
            case 'all':
                $properties = $this->property->getAllProperties([0,1,2,3]);
                $title = 'All';
            break;
            case 'sold':
                $properties = $this->property->getAllProperties([2]);
                $title = 'Sold';
            break;
            case 'available':
                $properties = $this->property->getAllProperties([0]);
                $title = 'Available';
            break;
            case 'reserved':
                $properties = $this->property->getAllProperties([3]);
                $title = 'Reserved';
                break;
            case 'rented':
                $properties = $this->property->getAllProperties([1]);
                $title = 'Rented';
            break;
            default:
                abort(404);
        }
        return view('property.index',[
            'properties'=>$properties,
            'title'=>$title
        ]);
    }

    private function validatePropertySubmission(Request $request){
        $this->validate($request,[
            'estate'=>'required',
            'street'=>'required',
            'paymentPlan'=>'required',
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
            "paymentPlan.required"=>"Select payment plan",
            "street.required"=>"Enter street",
            "propertyTitle.required"=>"Select property title",
            "buildingType.required"=>"What kind of building is this?",
            "withBQ.required"=>"Does this property has BQ? Choose the option that best describes it.",
            "propertyCondition.required"=>"What's the condition of this property?",
            "constructionStage.required"=>"Indicate property status",
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


    public function showBulkPropertyImportForm(Request $request){
        switch ($request->method()){
            case 'GET':
                return view('property.property-bulk-import');
            case 'POST':
                //return dd($request->all());
                $this->validate($request, [
                    'attachment'=>'required'
                ],[
                    'attachment.required'=>'Choose a file to upload',
                ]);
                $file = $request->attachment;

                $this->validate($request,[
                    'attachment'=>'required|max:5048',
                ],[
                    'attachment.required'=>'Choose a file to upload',
                    //'attachment.mimes'=>'Invalid file format. Upload either xlsx or xls file',
                    'attachment.max'=>'Maximum file upload size exceeded. Your file should not exceed 2MB'
                ]);
                $bulkimport = $this->propertyimportmaster->publishBulkImport($request, Auth::user()->id);
                Excel::import(new PropertyImport($request->firstRowHeader, $bulkimport->id),
                    public_path("assets/drive/import/{$bulkimport->attachment}"));
                session()->flash("success", "Success! Properties staged for import. Kindly review before carrying out the final operation. ");
                return back();
        }

    }


    public function manageImportedProperties(){
        return view("property.property-bulk-import-list",[
            "records"=>$this->propertyimportmaster->getAllRecords()
        ]);
    }


    public function showImportedPropertiesDetailList($batchCode){
        $record = $this->propertyimportmaster->getRecordByBatchCode($batchCode);
        if(empty($record)){
            session()->flash("error", "Whoops! No record found");
            return back();
        }
        return view("property.property-bulk-import-view",[
            "record"=>$record,
            'estates'=>$this->estate->getAllEstates(),
            'buildingTypes'=>BuildingType::getBuildingTypes(),
            'bqOptions'=>BqOption::getBQOptions(),
            'constructionStages'=>ConstructionStage::getConstructionStages(),
            'titles'=>PropertyTitle::getPropertyTitles(),
            'customers'=>$this->lead->getAllOrgLeads()
        ]);
    }

    public function updateImportedProperties(Request $request){
        foreach($request->records as $i => $record){
            $data = $this->propertyimportdetail->getEntryById($request->records[$i]);
            if(!empty($data)){
                $updatedRecord =
                    [
                    'estate_id' => $request->estate_id[$i] ?? 1 ,
                    'property_title' => $request->property_title[$i] ?? null ,
                    'property_name' => $request->property_name[$i] ?? null ,
                    'house_no' => $request->house_no[$i] ?? null,
                    'shop_no' => $request->shop_no[$i] ?? null,
                    'plot_no' => $request->plot_no[$i] ?? null,
                    'no_of_office_rooms' => $request->no_of_office_rooms[$i] ?? 0,
                    'office_ensuite_toilet_bathroom' => $request->office_ensuite_toilet_bathroom[$i] ?? null,
                    'no_of_shops' => $request->no_of_shops[$i] ?? 0,
                    'building_type' => $request->building_type[$i] ?? null,
                    'total_no_bedrooms' => $request->total_no_bedrooms[$i] ?? 0,
                    'with_bq' => $request->with_bq[$i] ?? null,
                    'no_of_floors' => $request->no_of_floors[$i] ?? 0,
                    'no_of_toilets' => $request->no_of_toilets[$i] ?? 0,
                    'no_of_car_parking' => $request->no_of_car_parking[$i] ?? 0,
                    'no_of_units' => $request->no_of_units[$i] ?? 0,
                    'price' => $request->price[$i] ?? 0,
                    'amount_paid' => $request->amount_paid[$i] ?? 0,
                    'property_condition' => $request->property_condition[$i] ?? null,
                    'construction_stage' => $request->constructionStage[$i] ?? null,
                    'land_size' => $request->land_size[$i] ?? null,
                    'description' => $request->description[$i] ?? null,
                    'occupied_by' => $request->occupied_by[$i] ?? null,
                    ];
                $data->update($updatedRecord);

            }
        }
        session()->flash("success", "Success! Your changes were saved.");
        return back();
    }


    public function actionPropertyRecord($recordId, $action){
        $record = $this->propertyimportdetail->getPropertyDetailById($recordId);
        if(empty($record)){
            session()->flash("error", "Whoops! Record does not exist");
            return back();
        }
        switch($action){
            case 'delete':
                $record->delete();
                session()->flash("success", "Success! Record deleted");
                return back();
            case 'approve':
                $record->action_status = 1;
                $record->actioned_by = Auth::user()->id;
                $record->date_actioned = now();
                $record->save();
                $item = $record;
                $this->__saveAndPostItem($item);
                session()->flash("success", "Success! Record posted");
                return back();
            case 'save-changes':
                $item = $record;

                $data = [
                    'estate_id' => request('estate_id') ?? 1 ,
                    'property_title' => request('property_title') ?? null ,
                    'property_name' => request('property_name') ?? null ,
                    'house_no' => request('house_no') ?? null,
                    'street' => request('street') ?? null,
                    'shop_no' => request('shop_no') ?? null,
                    'plot_no' => request('plot_no') ?? null,
                    'no_of_office_rooms' => request('no_of_office_rooms') ?? 0,
                    'office_ensuite_toilet_bathroom' => request('office_ensuite_toilet_bathroom') ?? null,
                    'no_of_shops' => request('no_of_shops') ?? 0,
                    'building_type' => request('building_type') ?? null,
                    'total_no_bedrooms' => request('total_no_bedrooms') ?? 0,
                    'with_bq' => request('with_bq') ?? null,
                    'no_of_floors' => request('no_of_floors') ?? 0,
                    'no_of_toilets' => request('no_of_toilets') ?? 0,
                    'no_of_car_parking' => request('no_of_car_parking') ?? 0,
                    'no_of_units' => request('no_of_units') ?? 0,
                    'price' => request('price') ?? 0,
                    'amount_paid' => request('amount_paid') ?? 0,
                    'property_condition' => request('property_condition') ?? null,
                    'construction_stage' => request('constructionStage') ?? null,
                    'land_size' => request('land_size') ?? null,
                    'description' => request('description') ?? null,
                    'occupied_by' => request('occupied_by') == 0 ? null : request('occupied_by'),
                    'added_by' => Auth::user()->id ?? null,
                    'sold_to' => request('occupied_by') == 0 ? null : request('occupied_by'),
                    'slug'=>Str::slug(request('property_name').'-'.substr(sha1( (time() + rand(99,9999)) ),32,40))
                ];
                $item->update($data);
                session()->flash("success", "Success! Changes saved.");
                return back();
            default:
                abort(404);

        }

    }

    private function __saveAndPostItem($item){

        if($item->amount_paid > 0){
            $item->payment_status = 1;
            $item->actioned_by = Auth::user()->id;
            $item->date_actioned = now();
            $item->action_status = 1;
            $item->save();
            if($item->amount_paid >= $item->price){
                $item->payment_status = 2;
                $item->save();
            }
        }

        $data = [
            'estate_id' => $item->estate_id ?? 1 ,
            'property_title' => $item->property_title ?? null ,
            'property_name' => $item->property_name ?? null ,
            'house_no' => $item->house_no ?? null,
            'street' => $item->street ?? null,
            'shop_no' => $item->shop_no ?? null,
            'plot_no' => $item->plot_no ?? null,
            'no_of_office_rooms' => $item->no_of_office_rooms ?? 0,
            'office_ensuite_toilet_bathroom' => $item->office_ensuite_toilet_bathroom ?? null,
            'no_of_shops' => $item->no_of_shops ?? 0,
            'building_type' => $item->building_type ?? null,
            'total_no_bedrooms' => $item->total_no_bedrooms ?? 0,
            'with_bq' => $item->with_bq ?? null,
            'no_of_floors' => $item->no_of_floors ?? 0,
            'no_of_toilets' => $item->no_of_toilets ?? 0,
            'no_of_car_parking' => $item->no_of_car_parking ?? 0,
            'no_of_units' => $item->no_of_units ?? 0,
            'price' => $item->price ?? 0,
            'amount_paid' => $item->amount_paid ?? 0,
            'property_condition' => $item->property_condition ?? null,
            'construction_stage' => $item->constructionStage ?? null,
            'land_size' => $item->land_size ?? null,
            'description' => $item->description ?? null,
            'occupied_by' => $item->occupied_by == 0 ? null : $item->occupied_by,
            'added_by' => Auth::user()->id ?? null,
            'sold_to' => $item->occupied_by == 0 ? null : $item->occupied_by,
            'slug'=>Str::slug($item->property_name).'-'.substr(sha1( (time() + rand(99,9999)) ),32,40)
        ];
        $property = Property::create($data);
        $estate = Estate::getEstateById($item->estate_id);
        if(!empty($estate)){
            $code = $estate->e_ref_code.$this->padNumber($property->id,6);
            $property->property_code = $code;
            $property->save();
        }
        $houseNo = $item->house_no ?? '';
        $service = "Invoice generated for  ".$item->property_name." for house number: $houseNo";
        //Generate invoice && then receipt
        if($item->amount_paid > 0){
            $invoiceStatus = 0;
            if($item->amount_paid >= $item->price){
                $invoiceStatus = 1;
            }
            $invoice = $this->invoicemaster->generateInvoice($property->id, $item->occupied_by, rand(99,9999),3,
                now(),date('Y-m-d', strtotime(now(). ' + 30 day')),
                $item->price,$item->price, $item->amount_paid,$invoiceStatus, $service, 1,
                $item->amount_paid);
            //Generate receipt now
            //$this->receipt->createNewReceipt($counter, $invoice, $amount, $charge, $method, $paymentDate)
            $this->receipt->createNewReceipt(rand(99,9999), $invoice, $item->amount_paid, 0,1, now());

        }
        //return true;
    }
    private function __saveItemOnly($item){



        $data = [
            'estate_id' => request('estate_id') ?? 1 ,
            'property_title' => request('property_title') ?? null ,
            'property_name' => request('property_name') ?? null ,
            'house_no' => request('house_no') ?? null,
            'street' => request('street') ?? null,
            'shop_no' => request('shop_no') ?? null,
            'plot_no' => request('plot_no') ?? null,
            'no_of_office_rooms' => request('no_of_office_rooms') ?? 0,
            'office_ensuite_toilet_bathroom' => request('office_ensuite_toilet_bathroom') ?? null,
            'no_of_shops' => request('no_of_shops') ?? 0,
            'building_type' => request('building_type') ?? null,
            'total_no_bedrooms' => request('total_no_bedrooms') ?? 0,
            'with_bq' => request('with_bq') ?? null,
            'no_of_floors' => request('no_of_floors') ?? 0,
            'no_of_toilets' => request('no_of_toilets') ?? 0,
            'no_of_car_parking' => request('no_of_car_parking') ?? 0,
            'no_of_units' => request('no_of_units') ?? 0,
            'price' => request('price') ?? 0,
            //'amount_paid' => $item->amount_paid ?? 0,
            'property_condition' => request('property_condition') ?? null,
            'construction_stage' => request('constructionStage') ?? null,
            'land_size' => request('land_size') ?? null,
            'description' => request('description') ?? null,
            'occupied_by' => request('occupied_by') == 0 ? null : request('occupied_by'),
            'added_by' => Auth::user()->id ?? null,
            'sold_to' => request('occupied_by') == 0 ? null : request('occupied_by'),
            'slug'=>Str::slug(request('property_name').'-'.substr(sha1( (time() + rand(99,9999)) ),32,40))
        ];
        PropertyBulkImportDetail::update($data);

    }


    public function discardPropertyRecord($batchCode){
        $record = $this->propertyimportmaster->getRecordByBatchCode($batchCode);
        if(empty($record)){
            session()->flash("error", "Whoops! Record does not exist");
            return back();
        }
        $record->status = 2;
        $record->save();
        session()->flash("success", "Success! Record discarded");
        return back();
    }

    public function postPropertyRecord($batchCode){
        $record = $this->propertyimportmaster->getRecordByBatchCode($batchCode);
        if(empty($record)){
            session()->flash("error", "Whoops! Record does not exist");
            return back();
        }
        $record->status = 1;
        $record->actioned_by = Auth::user()->id;
        $record->action_date = now();
        $record->save();

        $items = $this->propertyimportdetail->getPropertyDetailByMasterId($record->id);
        if(count($items) > 0){
            foreach($items as $key => $item){
                $paymentStatus = 0;
                if($item->amount_paid > 0){
                    $item->payment_status = 1;
                    $item->save();
                    if($item->amount_paid >= $item->price){
                        $item->payment_status = 2;
                        $item->save();
                    }
                }

                $data = [
                        'estate_id' => $item->estate_id ?? 1 ,
                        'property_title' => $item->property_title ?? null ,
                        'property_name' => $item->property_name ?? null ,
                        'house_no' => $item->house_no ?? null,
                        'street' => $item->street ?? null,
                        'shop_no' => $item->shop_no ?? null,
                        'plot_no' => $item->plot_no ?? null,
                        'no_of_office_rooms' => $item->no_of_office_rooms ?? 0,
                        'office_ensuite_toilet_bathroom' => $item->office_ensuite_toilet_bathroom ?? null,
                        'no_of_shops' => $item->no_of_shops ?? 0,
                        'building_type' => $item->building_type ?? null,
                        'total_no_bedrooms' => $item->total_no_bedrooms ?? 0,
                        'with_bq' => $item->with_bq ?? null,
                        'no_of_floors' => $item->no_of_floors ?? 0,
                        'no_of_toilets' => $item->no_of_toilets ?? 0,
                        'no_of_car_parking' => $item->no_of_car_parking ?? 0,
                        'no_of_units' => $item->no_of_units ?? 0,
                        'price' => $item->price ?? 0,
                        //'amount_paid' => $item->amount_paid ?? 0,
                        'property_condition' => $item->property_condition ?? null,
                        'construction_stage' => $item->constructionStage ?? null,
                        'land_size' => $item->land_size ?? null,
                        'description' => $item->description ?? null,
                        'occupied_by' => $item->occupied_by == 0 ? null : $item->occupied_by,
                        'added_by' => Auth::user()->id ?? null,
                        'sold_to' => $item->occupied_by == 0 ? null : $item->occupied_by,
                        'slug'=>Str::slug($item->property_name).'-'.substr(sha1( (time() + $key) ),32,40)
                    ];
                $property = Property::create($data);
                $estate = Estate::getEstateById($item->estate_id);
                if(!empty($estate)){
                    $code = $estate->e_ref_code.$this->padNumber($property->id,6);
                    $property->property_code = $code;
                    $property->save();
                }
                $houseNo = $item->house_no ?? '';
                $service = "Invoice generated for  ".$item->property_name." for house number: $houseNo";
                //Generate invoice && then receipt
                if($item->amount_paid > 0){
                    $invoiceStatus = 0;
                    if($item->amount_paid >= $item->price){
                        $invoiceStatus = 1;
                    }
                    $invoice = $this->invoicemaster->generateInvoice($property->id, $item->occupied_by, rand(99,9999),3,
                        now(),date('Y-m-d', strtotime(now(). ' + 30 day')),
                        $item->price,$item->price, $item->amount_paid,$invoiceStatus, $service, 1,
                        $item->amount_paid);
                    //Generate receipt now
                    $this->receipt->createNewReceipt(rand(99,9999), $invoice, $item->amount_paid, 0);

                }

            }
            session()->flash("success", "Success! Record posted");
            return back();
        }else{
            session()->flash("error", "Whoops! No record posted");
            return back();
        }

    }


    public function showPropertyAllocation(Request $request){
        //return dd($this->property->getAvailablePropertiesByEstateId(13)->pluck('id')->toArray());
        $method = $request->getMethod();
        switch ($method){
            case 'POST':
                $this->validate($request,[
                    "estate"=>"required",
                    "counter"=>"required",
                    "status"=>"required",
                    "property"=>"required|array",
                    "property.*"=>"required",
                    "customer"=>"required|array",
                    "customer.*"=>"required",
                    "allottee"=>"required|array",
                    "allottee.*"=>"required",
                ],[
                    "estate.required"=>"Choose an estate",
                    "counter.required"=>"",
                    "status.required"=>"Were these properties sold or rented?",
                    "property.required"=>"Information about property is missing",
                    "customer.*"=>"Choose who to allocate property to",
                    "customer.required"=>"Choose who to allocate property to",
                    "allottee.*"=>"Indicate level of allocation",
                    "allottee.required"=>"Indicate level of allocation",
                ]);
                $counter = $request->counter;
                if((count($request->property) < $counter) || (count($request->customer) < $counter) || (count($request->allottee) < $counter)){
                    session()->flash("error", "Whoops! All fields are required. Assign corresponding values for each record/property.");
                    return back();
                }
                $estateId = $request->estate;
                $propertyCounter = count($request->property);
                if($propertyCounter <= 0){
                    session()->flash("error", "Something went wrong. Try again.");
                    return back();
                }
                foreach($request->property as $key => $property){
                    $property = $this->property->getPropertyById($request->property[$key]);
                    if(!empty($property)){
                        //Allocate as first allottee on the property's table if it does not exist
                        if(is_null($property->occupied_by) && ($request->allottee[$key] == 1)){
                            $property->occupied_by = $request->customer[$key];
                            $property->sold_to = $request->customer[$key];
                            $property->status = $request->status ?? 2; //sold
                            $property->save();
                        }
                        //handle second,third,... allottees on the property allocation table
                        if($request->allottee[$key] != 1 ){
                            //check if level of allocation already exist on same property
                            $exist = $this->propertyallocation->checkExistingAllocation($estateId, $request->property[$key],
                                $request->allottee[$key]);
                            if(empty($exist)){
                                $this->propertyallocation->addAllocation($estateId, $request->property[$key],
                                    $request->customer[$key], $request->allottee[$key]);
                                $property->status = $request->status ?? 2; //sold
                                $property->save();
                            }
                        }
                    }

                }
                session()->flash("success", "Success! Property allocation done.");
                return back();
            case 'GET':
                return view('property.allocation',[
                    'estates'=>$this->estate->getAllEstates()
                ]);

        }
    }


    public function managePropertyAllocation(Request $request){
        $method = $request->getMethod();
        switch ($method){
            case 'GET':
                return view('property.manage-allocations',[
                    'allocations'=>$this->propertyallocation->getPropertyAllocationList()
                ]);
            case 'POST':
                $this->validate($request,[
                    'type'=>"required",
                    'allocation'=>"required",

                ],[
                    "type.required"=>"",
                    "allocation.required"=>"",
                ]);
                $allocation = $this->propertyallocation->getAllocationById($request->allocation);
                if(empty($allocation)){
                    session()->flash("error", "Whoops! Record not found.");
                    return back();
                }
                $allocation->status = $request->type;
                $allocation->date_actioned = now();
                $allocation->actioned_by = Auth::user()->id;
                $allocation->save();
                //get property
                $property = $this->property->getPropertyById($allocation->property_id);
                if(!empty($property) && ($request->type != 2)){
                    $property->status = 2; //sold
                    $property->save();
                }
                session()->flash("success", "Success! Property allocated.");
                return back();

        }
    }

    public function showProperties(Request $request){
        $this->validate($request,[
            "estate"=>"required"
        ],[
            "estate.required"=>"Choose estate"
        ]);
        $properties = $this->property->getAvailablePropertiesByEstateId($request->estate);
        $customers = $this->lead->getAllOrgLeads();
        return view('property.partial._property-allocation-list',[
            "properties"=>$properties,
            "customers"=>$customers,
        ]);
    }

}
