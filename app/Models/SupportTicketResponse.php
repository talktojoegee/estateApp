<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicketResponse extends Model
{
    use HasFactory;

    public function getRepliedBy(){
        return $this->belongsTo(User::class, 'user_id');
    }


    public function newRelpy($ticketId, $userId, $userType, $response){
        $reply = new SupportTicketResponse();
        $reply->ticket_id = $ticketId ?? null;
        $reply->user_id = $userId ?? null;
        $reply->user_type = $userType ?? null;
        $reply->response = $response ?? null;
        $reply->save();
        return $reply;
    }

    public function getTicketResponses($ticketId){
        return SupportTicketResponse::where('ticket_id', $ticketId)->orderBy('id', 'ASC')->get();
    }
}
