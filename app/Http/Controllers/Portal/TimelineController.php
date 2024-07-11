<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Traits\UtilityTrait;
use App\Models\AuthorizingPerson;
use App\Models\Calendar;
use App\Models\ChurchBranch;
use App\Models\EmailQueue;
use App\Models\Post;
use App\Models\PostAttachment;
use App\Models\PostComment;
use App\Models\PostCorrespondingPerson;
use App\Models\PostView;
use App\Models\Region;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TimelineController extends Controller
{
    use UtilityTrait;

    public function __construct()
    {
        $this->user = new User();
        $this->churchbranch = new ChurchBranch();
        $this->region = new Region();
        $this->post = new Post();
        $this->postcorrespondingpersons = new PostCorrespondingPerson();
        $this->calendar = new Calendar();
    }

    public function showTimeline(){
        $branchId = Auth::user()->branch ?? 1;
        if(empty($branchId)){
            abort(404);
        }
        //branch related
        $branchPostIds = $this->postcorrespondingpersons->getCorrespondingPosts(2,$branchId)
            ->pluck('pcp_post_id')->toArray();
        //user related
        $individualPosts = $this->postcorrespondingpersons->getCorrespondingPosts(4,Auth::user()->id)
            ->pluck('pcp_post_id')->toArray();
        //regional
        $regionId = 1;
        $regionalPosts = $this->postcorrespondingpersons->getCorrespondingPosts(3,$regionId)
            ->pluck('pcp_post_id')->toArray();
        //everyone
        $everyonePosts = $this->postcorrespondingpersons->getCorrespondingPosts(1,Auth::user()->id)
            ->pluck('pcp_post_id')->toArray();

        $postIds = array_merge($branchPostIds, $individualPosts, $regionalPosts, $everyonePosts);

        return view('timeline.index',[
            'branches'=>$this->churchbranch->getAllChurchBranches(),
            'regions'=>$this->region->getRegions(),
            'users'=>$this->user->getAllBranchUsers($branchId),
            'birthdays'=>$this->user->getCurrentNextBirthdays(),
            'posts'=>$this->post->getPostsByIds($postIds),
            'events'=>$this->calendar->getThisMonthsAppointments(),
        ]);
    }

    public function storeTimelinePost(Request $request){
        $this->validate($request,[
            'subject'=>'required',
            'postContent'=>'required',
            'type'=>'required',
            'to'=>'required',
        ],[
            "subject.required"=>"What's the subject?",
            "postContent.required"=>"Type the content in the field provided above.",
            "type.required"=>"What for of request is this?",
            "to.required"=>"Kindly indicate target",
        ]);
        $branch = Auth::user()->branch;
        $userId = Auth::user()->id;

        $post = Post::publishPost($userId, $branch, $request->subject, $request->postContent, $request->type,
            0, 76, null, null, 0, $request->to, null);
        $recipient = $request->to;
        //return dd($request->all());
        switch ($recipient){
            case 1: //for everyone
                $userIds = User::pluck('id');
                $values = json_encode($this->getIntegerArray($userIds));
                PostCorrespondingPerson::storeDetails($post->p_id, 1, $values);
                $message = "You're part of a new publication titled $request->subject. Kindly login to your account to access the details.";
                $this->queueEmailForSending($userIds, 'New Publication', $message);
                break;
            case 2: //For branch
                $this->validate($request,[
                   'branches'=>'required',
                ],['branches.required'=>"Select the church branch(es) concerned"]);
                $userIds = User::whereIn('branch', $request->branches)->pluck('id');
                $values = json_encode($this->getIntegerArray($request->branches));
                PostCorrespondingPerson::storeDetails($post->p_id, 2, $values);
                $message = "You're part of a new publication titled $request->subject. Kindly login to your account to access the details.";
                $this->queueEmailForSending($userIds, 'New Publication', $message);
                break;
            case 3: //For region
                $this->validate($request,[
                    'regions'=>'required',
                ],['regions.required'=>"Select the church regions concerned"]);
                //$userIds = User::whereIn('region', $request->regions)->pluck('id');
                $values = json_encode($this->getIntegerArray($request->regions));
                PostCorrespondingPerson::storeDetails($post->p_id, 3, $values);
                //$message = "You're part of a new publication titled $request->subject. Kindly login to your account to access the details.";
                //$this->queueEmailForSending($userIds, 'New Publication', $message);
                break;
            case 4: //For individuals
                $this->validate($request,[
                    'persons'=>'required',
                ],['persons.required'=>"Select the person(s) concerned"]);
                $values = json_encode($this->getIntegerArray($request->persons));
                PostCorrespondingPerson::storeDetails($post->p_id, 4, $values);
                break;

        }
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
        session()->flash("success", "Action successful!");
        return back();
    }

    public function readTimelinePost($slug){
        $post = $this->post->getPostBySlug($slug);

        if(empty($post)){
            abort(404);
        }
        $view = PostView::getPostViewByUserPostId(Auth::user()->id, $post->p_id);
        if(empty($view)){
            PostView::registerPostView(Auth::user()->id, $post->p_id);
        }
        $postType = $this->getPostType($post->p_type);
        $branches = [];
        $regions = null;
        $users = null;
        switch($post->p_scope){
            case 2: //branch
                $ids = $this->post->getCorrespondenceByPostId($post->p_id);
                $branches = $this->churchbranch->getChurchBranchByBranchIds(json_decode($ids->pcp_target));
                break;
            case 4: //Individuals
                $ids = $this->post->getCorrespondenceByPostId($post->p_id);
                $users = $this->user->getUserByIds(json_decode($ids->pcp_target));

        }

        return view('timeline.view',[
            'post'=>$post,
            'type'=>$postType,
            'scope'=>$post->p_scope,
            'branches'=>$branches,
            'users'=>$users,
            'regions'=>$regions
        ]);
    }

    public function postComment(Request $request){
        $this->validate($request,[
            'post'=>'required',
            'comment'=>'required'
        ],[
            "comment.required"=>"Type your comment in the field provided."
        ]);
        PostComment::leaveComment($request->post, Auth::user()->id,$request->comment,0);
        session()->flash("success", "Action successful!");
        return back();
    }


    public function showBirthdays(){
        return view("timeline.birthdays",[
            "users"=>$this->user->getAllUsers()
        ]);
    }


}
