<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Faqs;
use App\Models\SupportTicket;
use App\Models\SupportTicketResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    //


    public function __construct()
    {
        $this->ticket = new SupportTicket();
        $this->ticketresponse = new SupportTicketResponse();
        $this->faqs = new Faqs();
    }



    public function showTickets(){
        $authUser = Auth::user();
        return view('workflow.tickets',[
            'tickets'=> $authUser->type == 1 ? $this->ticket->getAllSupportTickets() : $this->ticket->getCompanySupportTickets($authUser->org_id),
        ]);
    }

    public function newTicket(Request $request){
        $authUser = Auth::user();
        $this->validate($request,[
            'subject'=>'required',
            'supportContent'=>'required',
        ],[
            'subject.required'=>"What's the subject of what you'll want to discuss with us?",
            'supportContent.required'=>"It's important you enter the details of your concern in the box provided.",
        ]);
        //$attachment = $this->uploadFile($request);
        $ticket = $this->ticket->newSupportTicket($request);
        $message = "$authUser->first_name submitted a new support ticket(".$ticket->ref_no.")";
        $subject = "New support ticket ";
        ActivityLog::registerActivity($authUser->org_id, null, $authUser->id, null, $subject, $message);
        session()->flash("success", "Action successful!");
        return redirect()->route('tickets');
    }

    public function showTicketDetails($slug){
        //$authUser = Auth::user();
        $ticket = $this->ticket->getSupportTicketByRefNo($slug);
        if(empty($ticket)){
            abort(404);
        }
        return view('workflow.ticket-details',[
            'ticket'=>$ticket,
            'responses'=>$this->ticketresponse->getTicketResponses($ticket->id)
        ]);

    }

    public function submitTicketReply(Request  $request){
        $authUser = Auth::user();
        $this->validate($request,[
            'ticketId'=>'required',
            'userType'=>'required',
            'response'=>'required',
        ],[
            'ticketId.required'=>'Whoops! Something went wrong.',
            'userType.required'=>'Whoops! Something went wrong.',
            'response.required'=>'Type your response here...',
        ]);
        $ticket = $this->ticket->getSupportTicketById($request->ticketId);
        if(empty($ticket)){
            abort(404);
        }
        $this->ticketresponse->newRelpy($request->ticketId, $authUser->id, $authUser->type == 1 ? 1:0, $request->response);
        session()->flash("success", "Action successful.");
        return back();
    }

    public function closeTicket(Request $request){
        $this->validate($request, [
            'ticketID'=>'required'
        ],[
            "ticketID.required"=>"Whoops! Something went wrong. Contact webmaster if the issue persist."
        ]);
        $ticket = $this->ticket->getSupportTicketById($request->ticketID);
        if(empty($ticket)){
            abort(404);
        }
        $ticket->date_closed = now();
        $ticket->closed_by = Auth::user()->first_name.' '.Auth::user()->last_name;
        $ticket->status = 1;
        $ticket->save();
        session()->flash("success", "Action successful!");
        return back();
    }

    public function showFaqs(){
        return view('workflow.faqs',['faqs'=>$this->faqs->getFaqs()]);
    }

    public function postFaq(Request $request){
        $this->validate($request,[
            'question'=>'required',
            'answer'=>'required'
        ],[
            'answer.required'=>"What's the answer to this question?",
            'question.required'=>"Enter a question with it's answer."
        ]);
        $this->faqs->publishFaq($request);
        /*$message = Auth::user()->first_name." published new FAQs";
        $subject = "New FAQ ";
        $this->auditlog->registerLog(Auth::user()->id, $subject, $message);*/
        session()->flash("success", "Your FAQs was published!");
        return back();
    }
    public function editFaq(Request $request){
        $this->validate($request,[
            'question'=>'required',
            'answer'=>'required',
            'faq'=>'required'
        ],[
            'answer.required'=>"What's the answer to this question?",
            'question.required'=>"Enter a question with it's answer."
        ]);
        $this->faqs->updateFaq($request);
        /*$message = Auth::user()->first_name." edited FAQ";
        $subject = "FAQ edited";
        $this->auditlog->registerLog(Auth::user()->id, $subject, $message);*/
        session()->flash("success", "Your changes were saved!");
        return back();
    }

}


