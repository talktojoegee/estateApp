<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\AppDefaultSetting;
use App\Models\AssignFrequency;
use App\Models\AuthorizingPerson;
use App\Models\ChurchBranch;
use App\Models\InvoiceDetail;
use App\Models\InvoiceMaster;
use App\Models\InvoicePaymentHistory;
use App\Models\InvoiceService;
use App\Models\Lead;
use App\Models\LicenceCategory;
use App\Models\Notification;
use App\Models\Post;
use App\Models\PostAttachment;
use App\Models\PostComment;
use App\Models\PostRadioDetail;
use App\Models\Property;
use App\Models\Receipt;
use App\Models\State;
use App\Models\User;
use App\Models\Workstation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RadioController extends Controller
{
    //
    public function __construct(){
        $this->state = new State();
        $this->station = new Workstation();
        $this->user = new User();
        $this->post = new Post();
        $this->licensecategory = new LicenceCategory();
        $this->appdefaultsetting = new AppDefaultSetting();
        $this->churchbranch = new ChurchBranch(); //this is seen as DEPARTMENT in this APP
        $this->postradiodetail = new PostRadioDetail();
        $this->invoicemaster = new InvoiceMaster();
        $this->assignfrequency = new AssignFrequency();
        $this->authorizingpersons = new AuthorizingPerson();

        $this->invoiceservice = new InvoiceService();
        $this->lead = new Lead();
        $this->property = new Property();
        $this->receipt = new Receipt();
        $this->invoicepaymenthistory = new InvoicePaymentHistory();

    }


    public function showNewApplicationForm(){
        $authUser = Auth::user();
        return view('company.license.new-application',[
            'stations'=>$this->station->getWorkstationsByCompanyId($authUser->org_id),
            'categories'=>$this->licensecategory->getLicenceCategories()
        ]);
    }

    public function showWorkstations(){

        return view('company.workstation.index',[
            'stations'=>$this->station->getWorkstationsByCompanyId(Auth::user()->org_id)
        ]);
    }


    public function showCreateWorkstationForm(){

        return view('company.workstation.create',[
            'locations'=>$this->state->getStatesByCountryId(161),
        ]);
    }

    public function storeWorkstation(Request $request){
        $this->validate($request,[
            'stationName'=>'required',
            'location'=>'required',
            'address'=>'required',
            'long'=>'required',
            'lat'=>'required',
            'mobileNo'=>'required'
        ],[
            "stationName.required"=>"Enter workstation name",
            "location.required"=>"Where is this location situated?",
            "long.required"=>"What's the longitude?",
            "lat.required"=>"What's the latitude?",
            "mobileNo.required"=>"Enter contact number for this workstation",
            "address.required"=>"Let's have the address of the workstation",
        ]);

        $station = $this->station->newWorkstation($request);
        $user = Auth::user();
        $log = "$user->first_name ($user->email) created a new workstation($station->name)";
        ActivityLog::registerActivity($user->org_id, null, $user->id, null, 'New workstation', $log);
        session()->flash("success", "Action successful!");
        return back();

    }
    public function editWorkstation(Request $request){
        $this->validate($request,[
            'stationName'=>'required',
            'location'=>'required',
            'address'=>'required',
            'long'=>'required',
            'lat'=>'required',
            'mobileNo'=>'required',
            'stationId'=>'required'
        ],[
            "stationName.required"=>"Enter workstation name",
            "location.required"=>"Where is this location situated?",
            "long.required"=>"What's the longitude?",
            "lat.required"=>"What's the latitude?",
            "mobileNo.required"=>"Enter contact number for this workstation",
            "address.required"=>"Let's have the address of the workstation",
            "stationId.required"=>''
        ]);

        $station = $this->station->editWorkstation($request);
        $user = Auth::user();
        $log = "$user->first_name ($user->email) made changes to($station->name) workstation";
        ActivityLog::registerActivity($user->org_id, null, $user->id, null, 'Workstation update', $log);
        session()->flash("success", "Your changes were saved.");
        return back();

    }


    public function showWorkstation($slug){
        $station = $this->station->getWorkstationBySlug($slug);
        if(empty($station)){
            session()->flash("error", "Whoops! Something went wrong.");
            return back();
        }
        return view('company.workstation.view',[
            'station'=>$station,
            'locations'=>$this->state->getStatesByCountryId(161) //Nigeria ID
        ]);
    }

    public function storeNewRadioApplication(Request $request){
        $this->validate($request,[
            'workstation'=>'required|array',
            'workstation.*'=>'required',
            'licence_category'=>'required|array',
            'licence_category.*'=>'required',
            'type_of_device'=>'required|array',
            'type_of_device.*'=>'required',
            'no_of_devices'=>'required|array',
            'no_of_devices.*'=>'required',
            'operation_mode'=>'required|array',
            'operation_mode.*'=>'required',
            'frequency_band'=>'required|array',
            'frequency_band.*'=>'required',
            'callSign'=>'required|array',
            'callSign.*'=>'required',
            'make'=>'required|array',
            'make.*'=>'required',
            'postContent'=>'required',
        ],[
            "postContent.required"=>"Briefly explain the purpose of this application.",
            'workstation.required'=>'Choose workstation',
            'workstation.*'=>'Choose workstation',
            'licence_category.required'=>'Select license categoryLet us know the type of device',
            'licence_category.*'=>'Select license categoryLet us know the type of device',
            'type_of_device.required'=>'Let us know the type of device',
            'type_of_device.*'=>'Let us know the type of device',
            'no_of_devices.required'=>'Indicate the number of devices',
            'no_of_devices.*'=>'Indicate the number of devices',
            'operation_mode.required'=>'Choose the mode of operation',
            'operation_mode.*'=>'Choose the mode of operation',
            'frequency_band.required'=>'Indicate frequency band',
            'frequency_band.*'=>'Indicate frequency band',
            'callSign.required'=>'Enter call sign',
            'callSign.*'=>'Enter call sign',
            'make.required'=>'Enter the make of the device/tool',
            'make.*'=>'Enter the make of the device/tool',
        ]);
        $authUser = Auth::user();
        $ref = substr(sha1(time()),31,40);
        $defaultSettings = $this->appdefaultsetting->getAppDefaultSettings();
        if(!empty($defaultSettings)){
            if(!empty($defaultSettings->new_app_section_handler)){
                $department = $defaultSettings->new_app_section_handler;

                $supervisor = $this->churchbranch->getActiveSupervisorByDepartmentId($department);
                if(!empty($supervisor)){
                    $post = Post::publishPost($authUser->id, 1, $ref, $request->postContent, 6,
                        0, 76, null, null, 1, 2, $request->category);
                    if($post){
                        $this->postradiodetail->setRadioLicenseApplicationDetails($request, $post->p_id);
                        #Register new workflow process
                        AuthorizingPerson::publishAuthorizingPerson($post->p_id, $supervisor->cb_head_pastor, 'Notified of new license application',0); //supervisorID
                        if ($request->hasFile('attachments')) {
                            foreach($request->attachments as $attachment){
                                $extension = $attachment->getClientOriginalExtension();
                                $size = $attachment->getSize();
                                $name = $attachment->getClientOriginalName();
                                $filename = uniqid() . '_' . time() . '_' . date('Ymd') . '.' . $extension;
                                $dir = 'assets/drive/cloud/';
                                $attachment->move(public_path($dir), $filename);
                                $file = new PostAttachment();
                                $file->pa_post_id = $post->p_id;
                                $file->pa_attachments = $filename;
                                $file->pa_size = $size ?? 'N/A';
                                $file->pa_name = $name ?? 'N/A';
                                $file->save();
                            }
                        }
                        #Admin notification
                        //send a notification to supervisor head
                        $title = "New Radio license application";
                        $log = $authUser->getUserOrganization->company_name." just submitted a new radio license application";
                        ActivityLog::registerActivity($authUser->org_id, null, $supervisor->cb_head_pastor, null, $title, $log);
                        Notification::setNewNotification($title, $log,
                            'show-application-details', $post->p_slug, 1, $supervisor->cb_head_pastor, $authUser->org_id);

                        #User notification
                        $subject = "New Radio license application";
                        $body = "Thank you for submitting your license application. Our team is on it. Do well to follow up with the process on this platform.";
                        ActivityLog::registerActivity($authUser->org_id, null, $authUser->id, null, $subject, $body);
                        Notification::setNewNotification($subject, $body,
                            'show-application-details', $post->p_slug, 1, $authUser->id, $authUser->org_id);
                        session()->flash("success", "Your radio license application was received. You can monitor your application process by logging into your account.");
                        return redirect()->route('manage-applications');
                    }else{
                        session()->flash("error", "Whoops! Something went wrong. Try again later.");
                        return back();
                    }

                }else{
                    session()->flash("error", "We can't process new applications at the moment. Try again later.");
                    return back();
                }

            }else{
                session()->flash("error", "We can't process new applications at the moment. Try again later.");
                return back();
            }
        }else{
            session()->flash("error", "We can't process new applications at the moment. Try again later.");
            return back();
        }

    }

    public function showManageApplications(){
        $authUser = Auth::user();
        return view('company.license.manage-applications',[
            'workflows'=> $authUser->type != 1 ?
                $this->post->getAllCompanyApplications(Auth::user()->org_id) :
                $this->post->getAllApplications(),
        ]);
    }

    public function showManageApplicationDetails($slug){
        $workflow = $this->post->getPostBySlug($slug);
        if(empty($workflow)){
            abort(404);
        }
        $authIds = AuthorizingPerson::pluckPendingAuthorizingPersonsByPostIdType($workflow->p_id,0);

        $userAction = AuthorizingPerson::getAuthUserAction($workflow->p_id,0, Auth::user()->id);
        return view('company.license.view',
            [
                'workflow'=>$workflow,
                'authIds'=>$authIds,
                'persons'=>$this->user->getAllAdminUsers(),
                'pendingId'=>0,
                'userAction'=>$userAction
            ]);
    }

    public function showGenerateInvoice($slug){
        $workflow = $this->post->getPostBySlug($slug);
        if(empty($workflow)){
            abort(404);
        }
        $authIds = AuthorizingPerson::pluckPendingAuthorizingPersonsByPostId($workflow->p_id);
        return view('company.license.generate-invoice',
            [
                'workflow'=>$workflow,
                'authIds'=>$authIds,
            ]);
    }


    public function generateInvoice(Request $request){
        $this->validate($request,[
            "postId"=>'required',
            "itemId"=>"required|array",
            "itemId.*"=>"required",
            "quantity"=>"required|array",
            "quantity.*"=>"required",
            "rate"=>"required|array",
            "rate.*"=>"required",
        ],[
            "postId.required"=>"",
            "itemId.required"=>"",
            "quantity.required"=>"Enter quantity",
            "rate.required"=>"Enter rate",
            "rate.*"=>"Rate is required"
        ]);
        $post = $this->post->getPostById($request->postId);

        if(empty($post)){
            session()->flash("error", "Whoops! No record found.");
            return back();
        }

        $invoice = $this->invoicemaster->createNewInvoice($request,$post);
        if(!empty($invoice)){
            $post->p_invoice_id = $invoice->id;
            $post->save();

            InvoiceDetail::setInvoiceDetail($invoice->id, $request);
        }else{
            session()->flash("error", "Whoops! Something went wrong.");
            return back();
        }
        session()->flash("success", "Action successful.");
        return back();
    }



    public function showNewInvoiceForm(){
        return view('company.invoice.new-invoice',[
            'customers'=>$this->lead->getAllOrgLeads(),
            'properties'=>$this->property->getAllProperties([0,1,2])
        ]);
    }


    public function storeNewInvoice(Request $request){
        if(empty($request->invoice_type)){
            session()->flash("error", "Whoops! Kindly select the type of invoice you want to issue");
            return back();
        }else{
            #Process invoice for new applicant
            //if($request->invoice_type == 1){
                if(empty($request->customer)){
                    session()->flash("error", "Whoops! Kindly select a customer");
                    return back();
                }else{
                    $this->handleInvoiceValidation($request);
                    $customer = $this->lead->getLeadById($request->customer);

                    if(!empty($customer)){
                        $invoice_no = $this->invoicemaster->getLatestInvoiceNo();
                        $invoice = $this->invoicemaster->newCustomerInvoice($request, $invoice_no);

                        //$activity = Auth::user()->first_name.' issued an invoice with the invoice No.'.$invoice->invoice_no;
                        //ActivityLog::logActivity(Auth::user()->tenant_id, Auth::user()->id, 0, 'New invoice', $activity);
                        session()->flash("success", "Action successful");
                        return back();
                    }else{
                        session()->flash("error", "Whoops! Something went wrong.");
                        return back();
                    }
                }

            //}

        }
    }

    private function handleInvoiceValidation(Request $request){
        $this->validate($request,[
            'invoice_type'=>'required',
            'customer'=>'required',
            'issue_date'=>'required|date',
            'due_date'=>'required|date',
            'service'=>'required|array',
            'service.*'=>'required'
        ],[
            "due_date.required"=>"Enter invoice due date",
            "due_date.date"=>"Enter a valid date",
            "issue_date.date"=>"Enter a valid date",
            "issue_date.required"=>"Enter invoice issue date",
            "customer.required"=>"Choose customer",
            "service.required"=>"Service or product is required",
            "service.*"=>"Enter at least one service",
        ]);
    }

    public function showManageInvoices($type){
        $authUser = Auth::user();
        switch ($type){
            case 'invoices':
                return view('company.invoice.index',[
                    'invoices'=> $authUser->type == 1 ? $this->invoicemaster->getAllInvoices([0,1,2,3,4]) : $this->invoicemaster->getAllCompanyInoices($authUser->org_id,[0,1,2,3]),
                    'title'=>'Invoices'
                ]);
            case 'pending':
                return view('company.invoice.index',[
                    'invoices'=> $authUser->type == 1 ? $this->invoicemaster->getAllInvoices([0]) : $this->invoicemaster->getAllCompanyInoices($authUser->org_id,[0]),
                    'title'=>'Pending Invoices'
                ]);
            case 'fully-paid':
                return view('company.invoice.index',[
                    'invoices'=> $authUser->type == 1 ? $this->invoicemaster->getAllInvoices([1]) : $this->invoicemaster->getAllCompanyInoices($authUser->org_id,[1]),
                    'title'=>'Fully-paid Invoices'
                ]);
            case 'partly-paid':
                return view('company.invoice.index',[
                    'invoices'=> $authUser->type == 1 ? $this->invoicemaster->getAllInvoices([2]) : $this->invoicemaster->getAllCompanyInoices($authUser->org_id,[1]),
                    'title'=>'Partly-paid Invoices'
                ]);
            case 'verified':
                return view('company.invoice.index',[
                    'invoices'=> $authUser->type == 1 ? $this->invoicemaster->getAllInvoices([4]) : $this->invoicemaster->getAllCompanyInoices($authUser->org_id,[2]),
                    'title'=>'Verified Invoices'
                ]);
            case 'declined':
                return view('company.invoice.index',[
                    'invoices'=> $authUser->type == 1 ? $this->invoicemaster->getAllInvoices([3]) : $this->invoicemaster->getAllCompanyInoices($authUser->org_id,[3]),
                    'title'=>'Declined Invoices'
                ]);

            default:
                abort(404);
        }


    }

    public function showInvoiceDetails($slug){
        $invoice = $this->invoicemaster->getInvoiceByRefNo($slug);
        $post = $this->post->getPostById($invoice->post_id);
        if(empty($invoice)){
            abort(404);
        }
        return view('company.invoice.view',
            [
                'invoice'=>$invoice,
                'workflow'=>$post
            ]);
    }

    public function submitProofOfPayment(Request $request){
        $this->validate($request,[
            'invoiceId'=>'required',
            'rrr'=>'required',
            'attachment'=>'required|mimes:jpeg,jpg,png,pdf',
        ],[
            "invoiceId.required"=>'',
            "rrr.required"=>"Enter the Remita Retrieval Reference(RRR)",
            "attachment.required"=>"Upload your proof of payment",
            "attachment.mimes"=>"Invalid file format"
        ]);
        $invoice = $this->invoicemaster->getInoviceById($request->invoiceId);
        if(empty($invoice)){
            session()->flash("error", "Whoops! Record not found.");
            return back();
        }
        if ($request->hasFile('attachment')) {
                $extension = $request->attachment->getClientOriginalExtension();
                //$name = $request->attachment->getClientOriginalName();
                $filename = uniqid() . '_' . time() . '_' . date('Ymd') . '.' . $extension;
                $dir = 'assets/drive/cloud/';
                $request->attachment->move(public_path($dir), $filename);
                $invoice->attachment = $filename;
                $invoice->rrr = $request->rrr;
                $invoice->amount_paid = $invoice->total ?? 0;
                $invoice->status = 1; //paid
                $invoice->save();
            //update post/license application too
            $post = $this->post->getPostById($invoice->post_id);
            if(!empty($post)){
                $post->p_invoice_id = $invoice->id;
                $post->p_amount = $invoice->total ?? 0;
                $post->p_status = 4;//paid
                $post->save();
            }
                #log
                #notification
            session()->flash("success", "Action successful");
            return back();
        }

    }


    public function actionPayment(Request $request){
        $this->validate($request,[
            'invoiceId'=>'required',
            'status'=>'required',
            'comment'=>'required'
        ],[
            "invoiceId.required"=>"",
            "status.required"=>"Whoops! Something went wrong.",
            "comment.required"=>"Leave a brief comment"
        ]);
        $invoice = $this->invoicemaster->getInoviceById($request->invoiceId);
        if(empty($invoice)){
            session()->flash("error", "Whoops! Record not found.");
            return back();
        }
        $invoice->actioned_by = Auth::user()->id;
        $invoice->comment = $request->comment ?? null;
        $invoice->date_actioned = now();
        //$invoice->status = $request->status;
        //$invoice->amount_paid = $request->status == 2 ? $invoice->total : 0;
        $invoice->save();
        //update post/license application too
        $post = $this->post->getPostById($invoice->post_id);
        //return dd($post);
        if(!empty($post)){
            $post->p_invoice_id = $invoice->id;
            $post->p_amount = $invoice->total ?? 0;
            $post->p_status = $request->status == 2 ? 5 : 4;//status == 2 ? payment verified // mark payment as paid
            $post->save();
        }
        //proceed with notification and activity log
        session()->flash("success", "Action successful");
        return back();
    }

    public function showApplicationCategory($type){

        $authUser = Auth::user();
        switch ($type){
            case 'verified':
                $posts = $authUser->type == 1 ?  $this->post->getAllPostByStatus(5) : $this->post->getAllOrgPostByStatus(5, $authUser->org_id);
                return view('company.license.application-status',[
                    'posts'=>$posts,
                    'title'=> $authUser->type == 1 ? 'Frequency Assignment' : 'Paid & Verified Applications'
                ]);
            case 'approved':
                $posts = $authUser->type == 1 ?  $this->post->getAllPostByStatus(2) : $this->post->getAllOrgPostByStatus(2, $authUser->org_id);
                return view('company.license.application-status',[
                    'posts'=>$posts,
                    'title'=>'Approved Applications'
                ]);
            case 'pending':
                $posts = $authUser->type == 1 ?  $this->post->getAllPostByStatus(0) : $this->post->getAllOrgPostByStatus(0, $authUser->org_id);
                return view('company.license.application-status',[
                    'posts'=>$posts,
                    'title'=>'Pending Applications'
                ]);
            case 'declined':
                $posts = $authUser->type == 1 ?  $this->post->getAllPostByStatus(3) : $this->post->getAllOrgPostByStatus(3, $authUser->org_id);
                return view('company.license.application-status',[
                    'posts'=>$posts,
                    'title'=>'Declined Applications'
                ]);
            case 'assigned':
                $posts = $authUser->type == 1 ?  $this->post->getAllPostByStatus(7) : $this->post->getAllOrgPostByStatus(7, $authUser->org_id);
                return view('company.license.application-status',[
                    'posts'=>$posts,
                    'title'=>'Assigned Frequencies'
                ]);

            default:
                abort(404);
        }
    }


    public function showAssignLicense($slug){
        $workflow = $this->post->getPostBySlug($slug);
        if(empty($workflow)){
            abort(404);
        }
        return view('company.license.assign-license',[
            'workflow'=>$workflow
        ]);
    }


    public function assignFrequency(Request $request){
        $this->validate($request,[
            "startDate"=>"required|date",
            "frequency"=>"required|array",
            "frequency.*"=>"required",
            "detailId"=>"required|array",
            "detailId.*"=>"required",
            "licenseNo"=>"required|unique:assign_frequencies,license_no",
        ],[
            "startDate.required"=>"Enter a start date",
            "frequency.required"=>"Enter frequency value",
            "frequency.*"=>"Enter frequency value",
            "detailId.required"=>"Whoops! Something went wrong.",
            "detailId.*"=>"Whoops! Something went wrong.",
            "licenseNo.unique"=>"This license number is currently in use.",
        ]);

        $oneDetail = $this->postradiodetail->getRadioDetailById($request->detailId[0]);
        if(!empty($oneDetail)) {

            $post = $this->post->getPostById($oneDetail->post_id);


            if(!empty($post)){

                $this->handleAssignment($request, 'assign', $post->p_id);
            }else{
                session()->flash("error", "Whoops! No record found.");
                return back();
            }


        }else{
            session()->flash("error", "No record found");
            return back();
        }

        //Initiate frequency assignment workflow
        $defaultSettings = $this->appdefaultsetting->getAppDefaultSettings();
        if(!empty($defaultSettings)) {
            if (!empty($defaultSettings->frequency_assignment_handler)) {
                $department = $defaultSettings->frequency_assignment_handler;
                $supervisor = $this->churchbranch->getActiveSupervisorByDepartmentId($department);
                if (!empty($supervisor)) {


                    #Register new workflow process
                    AuthorizingPerson::publishAuthorizingPerson($oneDetail->post_id, $supervisor->cb_head_pastor,
                        'Notified of frequency assignment', 1);

                }else{
                    $this->handleAssignment($request, 'unassigned', $oneDetail->post_id);
                    session()->flash("error", "Whoops! Something went wrong. Contact webmaster.");
                    return back();

                }
            }else{
                $this->handleAssignment($request, 'unassigned', $oneDetail->post_id);
                session()->flash("error", "Whoops! Something went wrong. Contact webmaster.");
                    return back();

            }
        }else{
            $this->handleAssignment($request, 'unassigned', $oneDetail->post_id);
            session()->flash("error", "Whoops! Something went wrong. Contact webmaster.");
            return back();
        }

        //proceed with notification and activity log
        session()->flash("success", "Action successful");
        return redirect()->route('manage-applications');
    }


    public function handleAssignment(Request $request, $operationType, $postId){

        $startDate = $request->startDate;
        $expiresAt = date('Y-m-d', strtotime($startDate. ' + 365 days'));
        $batchCode = substr(sha1(time()),29,40);
        $newAssignments = [];
        if($operationType == 'assign'){
            foreach($request->frequency as $key => $value){
                $detail = $this->postradiodetail->getRadioDetailById($request->detailId[$key]);
                if(!empty($detail)){
                    $post = $this->post->getPostById($detail->post_id);
                    if(!empty($post)){
                        $slug = $key."_".substr(sha1(time()),31,40).uniqid();
                        $licenseNo = $request->licenseNo ?? rand(9,9999);
                        $formNo = rand(10,100);
                        $formSerialNo = rand(7,100);
                        $record = AssignFrequency::addFrequencyDetails('new',$detail->post_id,$detail->id,$post->p_org_id,
                            $request->frequency[$key], $startDate, $expiresAt,$detail->workstation_id,
                            $detail->operation_mode, $detail->cat_id,$detail->frequency_band,$detail->type_of_device,
                            $batchCode,$slug, $detail->make, $formNo, $request->emission[$key], $request->effectiveRadiated[$key],
                            $detail->call_sign, $licenseNo, '-',$request->frequency[$key],
                            $formSerialNo);
                        array_push($newAssignments, $record->id);
                        //mark application as licensed
                        $post->p_status = 7;//frequency assigned.
                        $post->save();
                    }

                }
            }
        }else{
            AssignFrequency::destroy($newAssignments);
            $postRecord = Post::find($postId);
            $postRecord->p_status = 5;//take it back to verified
            $postRecord->save();
        }


    }

    public function showReviewFrequencyAssignment($slug){
        $workflow = $this->post->getPostBySlug($slug);
        if(empty($workflow)){
            abort(404);
        }
        $authIds = AuthorizingPerson::pluckPendingAuthorizingPersonsByPostIdType($workflow->p_id,1);
        $userAction = AuthorizingPerson::getAuthUserAction($workflow->p_id,1, Auth::user()->id);
        return view('company.license.review-frequency-assignment',[
            'workflow'=>$workflow,
            'authIds'=>$authIds,
            'persons'=>$this->user->getAllAdminUsers(),
            'pendingId'=>0,
            'userAction'=>$userAction,
            'assignment'=>$workflow->getFrequencyAssignmentDetails->first()
        ]);


    }
    public function updateFrequencyAssignment(Request $request){

        $this->validate($request,[
            "startDate"=>"required|date",
            "endDate"=>"required|date",
            "frequency"=>"required|array",
            "frequency.*"=>"required",
            "detailId"=>"required|array",
            "detailId.*"=>"required",
            "make"=>"required|array",
            "make.*"=>"required",
            "emission"=>"required|array",
            "emission.*"=>"required",
            "effectiveRadiated"=>"required|array",
            "effectiveRadiated.*"=>"required",
            "callSign"=>"required|array",
            "callSign.*"=>"required",
            "formNo"=>"required|array",
            "formNo.*"=>"required",
            "licenseNo"=>"required",
        ],[
            "startDate.required"=>"Enter a start date",
            "frequency.required"=>"What is the Max. Frequency & Tolerance",
            "frequency.*"=>"What is the Max. Frequency & Tolerance",
            "detailId.required"=>"Whoops! Something went wrong.",
            "detailId.*"=>"Whoops! Something went wrong.",
            "make.required"=>"Enter make",
            "make.*"=>"Enter value for make",
            "emission.required"=>"What should be the Emission Bandwidth",
            "emission.*"=>"What should be the Emission Bandwidth",
            "effectiveRadiated.required"=>"What is the Max. Effective Radiated Power",
            "effectiveRadiated.*"=>"What is the Max. Effective Radiated Power",
            "callSign.required"=>"What is the Call Sign",
            "callSign.*"=>"What is the Call Sign",
            "formNo.required"=>"Form No. is required",
            "formNo.*"=>"Form No. is required",
            "licenseNo.required"=>"License number is required",
        ]);
        $authUser = Auth::user();
        $assignedFrequency = $this->assignfrequency->getAssignedFrequencyById($request->detailId[0]);
        if(empty($assignedFrequency)){
            abort(404);
        }
        $detailId = $request->detailId[0] ?? null;
        $callSign = $request->callSign[0] ?? null;
        $emission = $request->emission[0] ?? null;
        $radiated = $request->effectiveRadiated[0] ?? null;
        $freq = $request->frequency[0] ?? null;
        $make = $request->make[0] ?? null;
        $formNo = $request->formNo[0] ?? null;
        $licenseNo = $request->licenseNo ?? null;
        $formSerialNo = rand(9,9999);
        $statement = "$authUser->title $authUser->first_name $authUser->last_name ($authUser->email) made the following changes:
            <p>From : <br>
                <strong>Call Sign:</strong> $assignedFrequency->call_sign  <br>
                <strong>Emission Bandwidth:</strong> $assignedFrequency->emission_bandwidth  <br>
                <strong>Max. Effective Radiated Power:</strong> $assignedFrequency->max_effective_rad  <br>
                <strong>Max. Frequency & Tolerance:</strong> $assignedFrequency->max_freq_tolerance  <br>
                <strong>Make:</strong> $assignedFrequency->make  <br>
                <strong>Form No.:</strong> $assignedFrequency->form_no  <br>
                <strong>License No.:</strong> $assignedFrequency->license_no  <br>
            </p>
            <p>To : <br>
                <strong>Call Sign:</strong> $callSign <br>
                <strong>Emission Bandwidth:</strong> $emission <br>
                <strong>Max. Effective Radiated Power:</strong> $radiated <br>
                <strong>Max. Frequency & Tolerance:</strong>  $freq <br>
                <strong>Make:</strong> $make <br>
                <strong>Form No.:</strong> $formNo <br>
                <strong>License No.:</strong> $licenseNo <br>
            </p>
        ";
        AssignFrequency::addFrequencyDetails('update',$assignedFrequency->post_id,$detailId,$assignedFrequency->org_id,
            $freq, $request->startDate, $request->endDate,$assignedFrequency->station_id,
            $assignedFrequency->mode, $assignedFrequency->category,$assignedFrequency->band,$assignedFrequency->type,
            $assignedFrequency->batch_code,$assignedFrequency->slug, $make, $formNo, $emission, $radiated,
            $callSign, $licenseNo, '-',$freq, $formSerialNo);
        PostComment::leaveComment($assignedFrequency->post_id, $authUser->id, $statement, 1);
        session()->flash("success", "Action successful");
        return back();


    }


    public function showCertificates($type){
        $authUser = Auth::user();
        switch ($type){
            case 'all':
                return view('company.license.certificates',[
                    'certificates'=> $authUser->type == 1 ? $this->assignfrequency->getAllCertificatesByStatus([0,1,2]) : $this->assignfrequency->getAllOrgCertificatesByStatus($authUser->org_id,[0,1,2]),
                    //'certificateBatch'=> $authUser->type == 1 ? $this->assignfrequency->getAllCertificates() : $this->assignfrequency->getAllGroupedOrgCertificates($authUser->org_id)
                ]);
            case 'valid':
                return view('company.license.certificates',[
                    'certificates'=> $authUser->type == 1 ? $this->assignfrequency->getAllCertificatesByStatus([1]) : $this->assignfrequency->getAllOrgCertificatesByStatus($authUser->org_id,[1]),
                ]);
            case 'expired':
                return view('company.license.certificates',[
                    'certificates'=> $authUser->type == 1 ? $this->assignfrequency->getAllCertificatesByStatus([2]) : $this->assignfrequency->getAllOrgCertificatesByStatus($authUser->org_id,[2]),
                ]);

            default:
                abort(404);
        }

    }

    public function showCertificateDetails($slug){
        $certificate = $this->assignfrequency->getCertificateByLicenseBySlug($slug);
        if(empty($certificate)){
            abort(404);
        }
        $finalApproval = $this->authorizingpersons->getAuthorizingPersonMarkedFinalByPostIdType($certificate->post_id,1);
        $lastForwardedApproval = $this->authorizingpersons->getLastApprovedAuthorizingPersonsByPostIdType($certificate->post_id,1);
        if(!empty($finalApproval) ){
            $finalPerson = $this->user->getUserById($finalApproval->ap_user_id);
            $lastForwarder = $this->user->getUserById($lastForwardedApproval->ap_user_id ?? 0) ?? [];
            return view('company.license.certificate-details',[
                'certificate'=> $certificate,
                'finalPerson'=>$finalPerson ?? [],
                'lastForwarder'=>$lastForwarder,
            ]);
        }else{
            session()->flash("error", "Whoops! Certificate is not ready.");
            return back();

        }

    }






    public function handleInvoiceService(Request $request){
        switch ($request->getMethod()){
            case 'GET':
                return view('company.invoice.service-setup',[
                    'services'=>$this->invoiceservice->getServices()
                ]);
            case 'POST':
                $this->validate($request,[
                    'serviceName'=>'required|unique:invoice_services,is_name'
                ],[
                    "serviceName.required"=>"Enter service name",
                    "serviceName.unique"=>"There is an existing service with this name"
                ]);
                $this->invoiceservice->addService($request);
                session()->flash("success", "Action successful");
                return back();

            case 'PUT':
                $this->validate($request,[
                    'serviceName'=>'required',
                    'serviceId'=>'required'
                ],[
                    "serviceName.required"=>"Enter service name",
                    "serviceId.required"=>''
                ]);
                $this->invoiceservice->editService($request);
                session()->flash("success", "Action successful");
                return back();
            default:
                abort(404);
        }
    }


    public function receivePayment(Request $request){
        $invoice = $this->invoicemaster->getInvoiceByRefNo($request->slug);
        if(!empty($invoice)){
            return view('company.invoice.receive-payment',['invoice'=>$invoice]);
        }else{
            session()->flash("error", " No record found.");
            return back();
        }
    }

    public function processPayment(Request $request){
        $this->validate($request,[
            'amount'=>'required',
            'invoice'=>'required'
        ],[
            'amount.required'=>'Enter the amount paid.',
            'invoice.required'=>''
        ]);
        $invoice = $this->invoicemaster->getInoviceById($request->invoice);
        if(!empty($invoice)){
            $balance = $invoice->total - $invoice->amount_paid;
            if($request->amount > $balance){
                session()->flash("error", "Whoops! The amount you entered is more than what's left as balance. ");
                return back();
            }

            $this->invoicemaster->updateInvoicePayment($invoice, $request->amount);
            $this->receipt->createNewReceipt(rand(9,9999), $invoice, $request->amount, 0); //($counter, $invoice, $amount, $charge)
            $this->invoicepaymenthistory->logPayment($invoice->id, 33, $request->amount, 0);
            //$activity = Auth::user()->first_name." received payment with an amount of (".env('APP_CURRENCY').$request->amount."). Invoice No.".$invoice->invoice_no;
            //ActivityLog::logActivity(Auth::user()->tenant_id, Auth::user()->id, 0, 'Received Payment', $activity);
            session()->flash("success", "Action successful");
            return back();
        }else{
            session()->flash("error", "Invoice does not exist. Try again.");
            return back();
        }
    }




    public function showManageReceipts(){
        return view('company.receipt.manage-receipts',[
            'receipts'=>$this->receipt->getAllTenantReceipts()
        ]);
    }

    public function showManageReceiptDetails(Request $request){
        $receipt = $this->receipt->getReceipt($request->slug);
        if(!empty($receipt)){
            return view('company.receipt.receipt-details',[
                'receipt'=>$receipt
            ]);
        }else{
            session()->flash("error", "No record found.");
            return back();
        }
    }

    public function approveReceipt(Request $request){
        $receipt = $this->receipt->approveReceipt($request->ref);
        if($receipt == 1){
            session()->flash("success", "Receipt approved and posted to ledger. ");
            return redirect()->route('show-manage-receipts');
        }else{
            session()->flash("error", "Something went wrong. Please try again or contact admin.");
            return redirect()->route('show-manage-receipts');
        }
    }

    public function declineReceipt(Request $request){
        $receipt = $this->receipt->declineReceipt($request->ref);
        if($receipt == 1){
            session()->flash("success", "Receipt declined. ");
            return redirect()->route('show-manage-receipts');
        }else{
            session()->flash("error", "Something went wrong. Please try again or contact admin.");
            return redirect()->route('show-manage-receipts');
        }
    }

/*
    public function sendReceiptAsEmail(Request $request){
        $receipt = $this->receipt->getReceipt($request->ref);
        if(!empty($receipt)){
            #Applicant
            if($receipt->receipt_type == 1){
                $applicant = $this->leaseapplication->getTenantLeaseApplicationById(Auth::user()->tenant_id,$receipt->applicant_id);
                if(!empty($applicant)){
                    $this->receipt->sendReceiptAsEmailService($receipt, $applicant);
                    $activity = Auth::user()->first_name." sent receipt via email. Receipt No.".$receipt->receipt_no;
                    ActivityLog::logActivity(Auth::user()->tenant_id, Auth::user()->id, 0, 'Receipt Emailed', $activity);
                    session()->flash("success", " Receipt sent");
                    return back();
                }else{
                    session()->flash("error", "There's no record for this applicant.");
                    return back();
                }
            }elseif($receipt->receipt_type == 2 || $receipt->receipt_type == 3){
                $tenant = $this->user->getTenantUserByUserTypeById(2, Auth::user()->id,$receipt->user_id);
                if(!empty($tenant)){
                    $tenant_app = $this->leaseapplication->getTenantLeaseApplicationById(Auth::user()->tenant_id,$receipt->applicant_id);
                    $this->receipt->sendReceiptAsEmailService($receipt, $tenant_app);
                    $activity = Auth::user()->first_name." sent receipt via email. Receipt No.".$receipt->receipt_no;
                    ActivityLog::logActivity(Auth::user()->tenant_id, Auth::user()->id, 0, 'Receipt Emailed', $activity);
                    session()->flash("success", " Receipt sent.");
                    return back();
                }else{
                    session()->flash("error", "There's no record for this tenant.");
                    return back();
                }
            }
        }else{
            session()->flash("error", " No record found.");
            return back();
        }
    }*/


    public function sendReceiptAsEmail(Request $request){
        $receipt = $this->receipt->getReceipt($request->ref);
        if(!empty($receipt)){
            $customer = $this->lead->getLeadById($receipt->customer_id);
            if(empty($customer)){
                abort(404);
            }
            session()->flash("success", "Email sent in demo mode");
            return back();
            #Applicant
            //if($receipt->receipt_type == 1){
                //$applicant = $this->leaseapplication->getTenantLeaseApplicationById(Auth::user()->tenant_id,$receipt->applicant_id);
               /* if(!empty($applicant)){
                    $this->receipt->sendReceiptAsEmailService($receipt, $applicant);
                    $activity = Auth::user()->first_name." sent receipt via email. Receipt No.".$receipt->receipt_no;
                    ActivityLog::logActivity(Auth::user()->tenant_id, Auth::user()->id, 0, 'Receipt Emailed', $activity);
                    session()->flash("success", " Receipt sent");
                    return back();
                }else{
                    session()->flash("error", "There's no record for this applicant.");
                    return back();
                }*/
            //}
        }else{
            session()->flash("error", " No record found.");
            return back();
        }
    }
}

