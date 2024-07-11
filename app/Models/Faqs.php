<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Faqs extends Model
{
    use HasFactory;

    public function getPostedBy(){
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function publishFaq(Request $request){
        $faq = new Faqs();
        $faq->answer = $request->answer;
        $faq->question = $request->question;
        $faq->posted_by = Auth::user()->id;
        $faq->save();
    }

    public function updateFaq(Request $request){
        $faq =  Faqs::find($request->faq);
        $faq->answer = $request->answer;
        $faq->question = $request->question;
        //$faq->posted_by = Auth::user()->id;
        $faq->save();
    }

    public function getFaqs(){
        return Faqs::orderBy('question', 'ASC')->get();
    }
}
