<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\AssignFrequency;
use App\Models\AuthorizingPerson;
use App\Models\CashBook;
use App\Models\CashBookAccount;
use App\Models\Currency;
use App\Models\Notification;
use App\Models\Post;
use App\Models\PostAttachment;
use App\Models\PostComment;
use App\Models\TransactionCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkflowController extends Controller
{

    public function __construct()
    {
        $this->post = new Post();
        $this->currency = new Currency();
        $this->user = new User();
        $this->transactioncategory = new TransactionCategory();
        $this->authorizingperson = new AuthorizingPerson();
        $this->cashbook = new CashBook();
        $this->cashbookaccount = new CashBookAccount();

    }

    public function showWorkflowView(){
        return view('workflow.index',[
            'persons'=>$this->user->getAllBranchUsers(Auth::user()->branch),
            'currencies'=>$this->currency->getCurrencies(),
            'workflows'=>$this->post->getAllPersonalWorkflow(Auth::user()->id),
            'categories'=>$this->transactioncategory->getBranchCategoriesByType(Auth::user()->branch, 2),
        ]);
    }


    public function storeWorkflowRequest(Request $request){
        $this->validate($request,[
           'subject'=>'required',
           'amount'=>'required',
           'authPerson'=>'required',
           'currency'=>'required',
           'description'=>'required',
           'type'=>'required',
           'category'=>'required',
        ],[
            "subject.required"=>"What's the subject of this request?",
            "amount.required"=>"What is the amount you intend to request for?",
            "currency.required"=>"Choose the currency associated to this amount",
            "authPerson.required"=>"Who is the first person that's expected to act on this request?",
            "description.required"=>"Briefly explain the purpose of this request.",
            "type.required"=>"What for of request is this?",
            "category.required"=>"Select category",
        ]);
        $branch = Auth::user()->branch;

        $post = Post::publishPost(Auth::user()->id, $branch, $request->subject, $request->description, $request->type,
            $request->amount, $request->currency, null, null, 1, 2, $request->category);
        AuthorizingPerson::publishAuthorizingPerson($post->p_id, $request->authPerson,'Notified', 0);
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
        session()->flash("success", "Success! Action successful.");
        return back();
    }

    public function viewWorkflowRequest($slug){
        $workflow = $this->post->getPostBySlug($slug);

        if(empty($workflow)){
            abort(404);
        }
        $authIds = AuthorizingPerson::pluckPendingAuthorizingPersonsByPostId($workflow->p_id);
        return view('workflow.view',
            [
                'workflow'=>$workflow,
                'authIds'=>$authIds,
                'persons'=>$this->user->getAllBranchUsers(Auth::user()->branch),
                'pendingId'=>0
            ]);
    }

    public function commentOnPost(Request $request){
        $this->validate($request,[
            'postId'=>'required',
            'comment'=>'required',
            //'type'=>'required'
        ],[
            'postId.required'=>'',
            'comment.required'=>'Type your comment in the field provided'
        ]);
        $userId = Auth::user()->id;
        $comment = $request->comment;
        //return response()->json(['userId'=>$userId, 'postId'=>$request->postId, 'comment'=>$comment]);
        PostComment::leaveComment($request->postId, $userId, $comment,$request->type);
        $comments = PostComment::getCommentsByPostId($request->postId, $request->type);
        return view('workflow.partials._comments',[
            'comments'=>$comments
        ]);
    }

    public function updateWorkflowStatus(Request $request){
        if(isset($request->final)){
            $this->validate($request, [
                'workflowId'=>'required',
                'authId'=>'required',
                'comment'=>'required',
                'status'=>'required',
            ],[
                'comment.required'=>'Type comment in the field provided',
                'authId'=>''
            ]);
        }else{
            $this->validate($request, [
                'workflowId'=>'required',
                'comment'=>'required',
                'nextAuth'=>'required',
                'authId'=>'required',
                'status'=>'required',
            ],[
                'nextAuth.required'=>'Choose who is next to act on this',
                'comment.required'=>'Type comment in the field provided',
                'authId.required'=>''
            ]);
        }
        $authUser = Auth::user();
        $userId = $authUser->id;
        $postId = $request->workflowId;
        $final = isset($request->final) ? 1 : 0;
        $status = $request->status;
        $authId = $request->authId;
        $post = $this->post->getPostById($postId);
        if(empty($post)){
            session()->flash("error", "Whoops! Something went wrong. Record not found.");
            return back();
        }
        $authorizingPerson = AuthorizingPerson::getAuthorizingPerson($postId,$authId, $userId);
        if(empty($authorizingPerson)){
            abort(404);
        }
        AuthorizingPerson::updateStatus($postId, $authId, $userId, $status, $request->comment, $final);
        $type = $authorizingPerson->ap_type == 1 ? 1 : 0;
        $routeName = $authorizingPerson->ap_type == 1 ? 'review-assignment' : 'show-application-details';
        if($final == 1){
            //Post::updatePostStatus($postId, $status);
            $post->p_status = $authorizingPerson->ap_type == 0 ? 2 : 6; //if frequency assignment is marked as final, issue license
            $post->save();
            #User notification
            $subject = "Good news!";
            $body = $type == 1 ? "Your certificate is ready!" : "Your application is approved. Hold on...";
            ActivityLog::registerActivity($post->p_org_id, null, $post->p_posted_by, null, $subject, $body);
            Notification::setNewNotification($subject, $body,
                $routeName, $post->p_slug, 1, $post->p_posted_by, $post->p_org_id);
            //update assign frequency status
            if($type == 1){
                $records = AssignFrequency::getAssignedFrequenciesByPostId($postId);
                foreach ($records as $record){
                    $record->status = 1;//mark as active
                    $record->save();
                }
            }

            #Push to cashbook
            //$workflow = $this->post->getPostById($postId);
            /*if(!empty($workflow) && ($workflow->p_type == 6)){
                $note = $workflow->p_title.' '.$workflow->p_content;
                $cashbookAccount = $this->cashbookaccount->getBranchFirstAccount($workflow->p_branch_id);
                $this->cashbook->addCashBook($workflow->p_branch_id, $workflow->p_category_id, $cashbookAccount->cba_id,
                    $workflow->p_currency, 1, 0, 2, now(),
                    $note, $note,
                    $workflow->p_amount,  0, substr(sha1(time()),31,40), date('m'), date('Y'));
            }*/
        }else{
            #User notification
            $subject = $final != 1 ? "Update: License Application" : "Congratulations! Application Approved.";
            $body = $final != 1 ? "There was an update on your license application." : "We're glad to inform you that your radio license application was approved. Await further actions.";
            ActivityLog::registerActivity($post->p_org_id, null, $post->p_posted_by, null, $subject, $body);
            Notification::setNewNotification($subject, $body,
                $routeName, $post->p_slug, 1, $post->p_posted_by, $post->p_org_id);

            AuthorizingPerson::publishAuthorizingPerson($postId, $request->nextAuth, 'Notified of new license application',$type);
            #Next person notification
            //send a notification to the authorizing officer
            $title = "Update: License Application";
            $user_title = $authUser->title ?? null;
            $log = "$user_title $authUser->first_name($authUser->email) forwarded an application to your desk.";
            ActivityLog::registerActivity($post->p_org_id, null, $request->nextAuth, null, $title, $log);
            Notification::setNewNotification($title, $log,
                $routeName, $post->p_slug, 1, $request->nextAuth, $post->p_org_id);
        }

        session()->flash("success", "Action successful!");
        return back();

    }
}
