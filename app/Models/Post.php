<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;
    protected $primaryKey = 'p_id';

    public function getCurrency(){
        return $this->belongsTo(Currency::class, 'p_currency');
    }


    public function getCompany(){
        return $this->belongsTo(Organization::class, 'p_org_id');
    }


    public function getRadioLicenseDetails(){
        return $this->hasMany(PostRadioDetail::class, 'post_id');
    }

    public function getFrequencyAssignmentDetails(){
        return $this->hasMany(AssignFrequency::class, 'post_id');
    }




    public function getAuthor(){
        return $this->belongsTo(User::class, 'p_posted_by');
    }

    public function getAuthorizingPersons(){
        return $this->hasMany(AuthorizingPerson::class, 'ap_post_id')->where('ap_type',0);
    }
    public function getFrequencyAssignmentAuthorizingPersons(){
        return $this->hasMany(AuthorizingPerson::class, 'ap_post_id')->where('ap_type',1);
    }
    public function getPostViews(){
        return $this->hasMany(PostView::class, 'pv_post_id');
    }

    public function getPostComments(){
        return $this->hasMany(PostComment::class, 'pc_post_id')->where('pc_type',0)->orderBy('pc_id', 'DESC');
    }
    public function getFrequencyPostComments(){
        return $this->hasMany(PostComment::class, 'pc_post_id')->where('pc_type',1)->orderBy('pc_id', 'DESC');
    }

    public function getAttachments(){
        return $this->hasMany(PostAttachment::class, 'pa_post_id');
    }

    public function getCategory(){
        return $this->belongsTo(TransactionCategory::class, 'p_category_id');
    }
    public function getBranch(){
        return $this->belongsTo(ChurchBranch::class, 'p_category_id');
    }

    public static function publishPost($author, $branch, $title, $content, $type = 1,
    $amount = 0, $currency=76, $startDate, $endDate, $authorization, $scope = 2, $cat=null){
        $post = new Post();
        $post->p_posted_by = $author;
        $post->p_branch_id = $branch;
        $post->p_title = $title;
        $post->p_content = $content;
        $post->p_type = $type;
        $post->p_amount = $amount ?? 0;
        $post->p_currency = $currency ?? 76;
        $post->p_start_date = $startDate;
        $post->p_end_date = $endDate;
        $post->p_authorization = $authorization;
        $post->p_scope = $scope;
        $post->p_category_id = $cat;
        $post->p_slug = Str::slug($title).'-'.substr(sha1(time()),31,40);
        $post->p_org_id = Auth::user()->org_id;
        $post->save();
        return $post;
    }

    public function getAllPersonalPosts($authorId){
        return Post::where('p_posted_by', $authorId)->orderBy('p_id', 'DESC')->get();
    }

    public function getAllPersonalWorkflow($authorId){
        return Post::where('p_posted_by', $authorId)
            ->where('p_type', 6)
            ->orWhere('p_type',7)
            ->orderBy('p_id', 'DESC')->get();
    }
    public function getAllCompanyApplications($companyId){
        return Post::where('p_org_id', $companyId)
            ->where('p_type', 6)
            //->orWhere('p_type',7)
            ->orderBy('p_id', 'DESC')->get();
    }

    public function getAllApplications(){
        return Post::where('p_type', 6)
            ->orderBy('p_id', 'DESC')->get();
    }

    public function getAllBranchPosts($branchId){
        return Post::where('p_branch_id', $branchId)->orderBy('p_id', 'DESC')->get();
    }

    public function getPostById($postId){
        return Post::find($postId);
    }
    public function getPostBySlug($slug){
        return Post::where('p_slug', $slug)->first();
    }

    public static function updatePostStatus($postId, $status){
        $post = Post::find($postId);
        $post->p_status = $status;
        $post->save();
    }

    public function getAllOrgPostByStatus($status, $orgId){
        return Post::where('p_status', $status)->where('p_org_id', $orgId)->where('p_type',6)->orderBy('p_id', 'DESC')->get();
    }
    public function getAllPostByStatus($status){
        return Post::where('p_status', $status)->where('p_type',6)->orderBy('p_id', 'DESC')->get();
    }



    public function getPostsByIds($postIds){
        return Post::whereIn('p_id', $postIds)->orderBy('p_id', 'DESC')->paginate(10);
    }

    public function getCorrespondenceByPostId($postId){
        return PostCorrespondingPerson::where('pcp_post_id', $postId)->first();
    }


}
