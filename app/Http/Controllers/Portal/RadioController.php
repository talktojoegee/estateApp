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
use App\Models\LicenceCategory;
use App\Models\Notification;
use App\Models\Post;
use App\Models\PostAttachment;
use App\Models\PostRadioDetail;
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
                        AuthorizingPerson::publishAuthorizingPerson($post->p_id, $supervisor->cb_head_pastor); //supervisorID
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
        $authIds = AuthorizingPerson::pluckPendingAuthorizingPersonsByPostId($workflow->p_id);
        return view('company.license.view',
            [
                'workflow'=>$workflow,
                'authIds'=>$authIds,
                'persons'=>$this->user->getAllAdminUsers(),
                'pendingId'=>0
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
            InvoiceDetail::setInvoiceDetail($invoice->id, $request);
        }else{
            session()->flash("error", "Whoops! Something went wrong.");
            return back();
        }
        session()->flash("success", "Action successful.");
        return back();
    }

    public function showManageInvoices(){
        $authUser = Auth::user();
        return view('company.invoice.index',[
            'invoices'=> $authUser->type == 1 ? $this->invoicemaster->getAllInvoices() : $this->invoicemaster->getAllCompanyInoices($authUser->org_id)
        ]);
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
        $invoice->status = $request->status;
        $invoice->amount_paid = $request->status == 2 ? $invoice->total : 0;
        //$invoice->save();
        //update post/license application too
        $post = $this->post->getPostById($invoice->post_id);
        //return dd($post);
        if(!empty($post)){
            $post->p_invoice_id = $invoice->id;
            $post->p_amount = $invoice->total ?? 0;
            $post->p_status = 5;//payment verified || Decline
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
            "detailId.*"=>"required"
        ],[
            "startDate.required"=>"Enter a start date",
            "frequency.required"=>"Enter frequency value",
            "frequency.*"=>"Enter frequency value",
            "detailId.required"=>"Whoops! Something went wrong.",
            "detailId.*"=>"Whoops! Something went wrong."
        ]);
        $startDate = $request->startDate;
        $expiresAt = date('Y-m-d', strtotime($startDate. ' + 365 days'));
        $batchCode = substr(sha1(time()),29,40);
        foreach($request->frequency as $key => $value){
            $detail = $this->postradiodetail->getRadioDetailById($request->detailId[$key]);
            if(!empty($detail)){
                $post = $this->post->getPostById($detail->post_id);
                if(!empty($post)){
                    $slug = $key."_".substr(sha1(time()),31,40).uniqid();
                    $licenseNo = rand(100,10000);
                    $formNo = rand(10,100);
                    $formSerialNo = rand(7,100);
                    AssignFrequency::addFrequencyDetails($detail->post_id,$detail->id,$post->p_org_id,
                        $request->frequency[$key], $startDate, $expiresAt,$detail->workstation_id,
                        $detail->operation_mode, $detail->cat_id,$detail->frequency_band,$detail->type_of_device,
                        $batchCode,$slug, $detail->make, $formNo, $request->emission[$key], $request->effectiveRadiated[$key],
                        $detail->call_sign, $licenseNo, '-',$request->frequency[$key],
                        $formSerialNo);
                    //mark application as licensed
                    $post->p_status = 6;//licensed
                    $post->save();
                }

            }
        }
        //proceed with notification and activity log
        session()->flash("success", "Action successful");
        return redirect()->route('manage-applications');
    }


    public function showCertificates(){
        $authUser = Auth::user();
        return view('company.license.certificates',[
            'certificates'=> $authUser->type == 1 ? $this->assignfrequency->getAllCertificates() : $this->assignfrequency->getAllOrgCertificates($authUser->org_id),
            //'certificateBatch'=> $authUser->type == 1 ? $this->assignfrequency->getAllCertificates() : $this->assignfrequency->getAllGroupedOrgCertificates($authUser->org_id)
        ]);
    }

    public function showCertificateDetails($slug){
        $certificate = $this->assignfrequency->getCertificateByLicenseBySlug($slug);
        if(empty($certificate)){
            abort(404);
        }
        return view('company.license.certificate-details',[
            'certificate'=> $certificate
        ]);
    }
}
