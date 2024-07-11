<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthorizingPerson extends Model
{
    use HasFactory;

    protected $primaryKey = 'ap_id';

    public function getUser(){
        return $this->belongsTo(User::class, 'ap_user_id', 'id');
    }

    public static function publishAuthorizingPerson($postId, $userId, $comment, $type){
        $auth = new AuthorizingPerson();
        $auth->ap_post_id = $postId;
        $auth->ap_user_id = $userId;
        $auth->ap_comment = $comment;
        $auth->ap_type = $type;
        $auth->save();
    }

    public static function updateStatus($postId,$authId, $userId, $status, $comment, $final = 0){
        $update = AuthorizingPerson::where('ap_post_id', $postId)
            ->where('ap_id', $authId)->where('ap_user_id', $userId)->first();
        $update->ap_status = $status;
        $update->ap_final = $final;
        $update->ap_comment = $comment ?? null;
        $update->save();
    }
    public static function getAuthorizingPerson($postId,$authId, $userId){
        return AuthorizingPerson::where('ap_post_id', $postId)
            ->where('ap_id', $authId)->where('ap_user_id', $userId)->first();}

    public static function pluckPendingAuthorizingPersonsByPostId($postId){
        return AuthorizingPerson::where('ap_status',0)->where('ap_post_id', $postId)->pluck('ap_user_id')->toArray();
    }
    public static function pluckPendingAuthorizingPersonsByPostIdType( $postId, $type){
        return AuthorizingPerson::where('ap_post_id', $postId)->where('ap_type', $type)->pluck('ap_user_id')->toArray();
    }

    public static function getAuthUserAction( $postId, $type,$userId){
        return AuthorizingPerson::where('ap_user_id', $userId)->where('ap_post_id', $postId)->where('ap_type', $type)->first();
    }

    public function getLastApprovedAuthorizingPersonsByPostIdType($postId, $type){
        return AuthorizingPerson::where('ap_post_id', $postId)->where('ap_type', $type)
            ->where('ap_status', 1)->where('ap_final',0)->orderBy('ap_id', 'DESC')->first();
    }
    public function getAuthorizingPersonMarkedFinalByPostIdType($postId, $type){
        return AuthorizingPerson::where('ap_post_id', $postId)->where('ap_type', $type)->where('ap_final',1)->where('ap_status', 1)->orderBy('ap_id', 'DESC')->first();
    }
}
