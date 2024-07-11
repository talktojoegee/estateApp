<?php

namespace App\Models;

use App\Http\Traits\UtilityTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportTicket extends Model
{
    use HasFactory;
    use UtilityTrait;

    public function getAuthor(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getCompany(){
        return $this->belongsTo(Organization::class, 'org_id');
    }

    public function getAllSupportTickets(){
        return SupportTicket::orderBy('id', 'DESC')->get();
    }

    public function newSupportTicket(Request $request){
        $support = new SupportTicket();
        $support->user_id = Auth::user()->id;
        $support->subject = $request->subject ?? null;
        $support->content = $request->supportContent ?? null;
        $support->attachment = $this->uploadFile($request) ?? null;
        $support->ref_no = substr(sha1(time()),32,40);
        $support->org_id = Auth::user()->org_id;
        $support->save();
        return $support;
    }

    public function getSupportTicketByRefNo($refNo){
        return SupportTicket::where('ref_no', $refNo)->first();
    }

    public function getCompanySupportTickets($orgId){
        return SupportTicket::where('org_id', $orgId)->orderBy('id', 'DESC')->get();
    }



    public function getSupportTicketById($ticketId){
        return SupportTicket::find($ticketId);
    }
}
