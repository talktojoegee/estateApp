<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Lead extends Model
{
    use HasFactory;

    public function getLogs(){
        return $this->hasMany(ActivityLog::class, 'lead_id')->orderBy('id', 'DESC');
    }
    public function getCustomerFiles(){
        return $this->hasMany(FileModel::class, 'lead_id')->orderBy('id', 'DESC');
    }
    public function getAddedBy(){
        return $this->belongsTo(User::class, 'added_by');
    }
    public function getStatus(){
        return $this->belongsTo(LeadStatus::class, 'status');
    }
    public function getSource(){
        return $this->belongsTo(LeadSource::class, 'source_id');
    }

    public function getLeadNotes(){
        return $this->hasMany(LeadNote::class, 'lead_id')->orderBy('id', 'DESC');
    }
    public function addLead(Request $request):Lead{
        $lead = new Lead();
        $lead->entry_date = $request->date ??  now();
        $lead->added_by = Auth::user()->id;
        $lead->org_id = Auth::user()->org_id;
        $lead->first_name = $request->firstName;
        $lead->last_name = $request->lastName;
        $lead->email = $request->email;
        $lead->phone = $request->mobileNo;
        $lead->dob = $request->birthDate;
        $lead->source_id = $request->source;
        $lead->status = $request->status;
        $lead->gender = $request->gender;
        $lead->street = $request->street ?? null;
        $lead->city = $request->city ?? null;
        $lead->state = $request->state ?? null;
        $lead->code = $request->code ?? null;
        $lead->entry_month = date('m',strtotime($request->date));
        $lead->entry_year = date('Y',strtotime($request->date));
        $lead->slug = Str::slug($request->firstName).'-'.Str::random(8);
        $lead->save();
        return $lead;
    }
    public function editLead(Request $request):Lead{
        $lead =  Lead::find($request->leadId);
        $lead->first_name = $request->firstName;
        $lead->last_name = $request->lastName;
        $lead->email = $request->email;
        $lead->phone = $request->mobileNo;
        $lead->dob = $request->birthDate;
        $lead->source_id = $request->source;
        $lead->status = $request->status;
        $lead->gender = $request->gender;
        $lead->street = $request->street ?? null;
        $lead->city = $request->city ?? null;
        $lead->state = $request->state ?? null;
        $lead->code = $request->code ?? null;
        $lead->slug = Str::slug($request->firstName).'-'.Str::random(8);
        $lead->save();
        return $lead;
    }

    public function getAllOrgLeads(){
        return Lead::orderBy('first_name', 'ASC')->get();
    }

    public function getLeadBySlug($slug){
        return Lead::where('slug', $slug)->first();
    }

    public function getLeadById($id){
        return Lead::find($id);
    }





    public function getLeadByMonthYear($month, $year){
        return Lead::where('entry_month', $month)->where('entry_year', $year)->orderBy('id', 'DESC')->get();
    }

    public function getTotalLeadsByDateRange($startDate, $endDate){
        return Lead::select(
            DB::raw("DATE_FORMAT(entry_date, '%m-%Y') monthYear"),
            DB::raw("YEAR(entry_date) year, MONTH(entry_date) month"),
            DB::raw("COUNT(id) total"),
            'entry_date',
        )->whereBetween('entry_date', [$startDate, $endDate])
            //->where('a_branch_id', Auth::user()->branch)
            ->orderBy('month', 'ASC')
            ->groupby('year','month')
            ->get();
    }


    public static function getCustomerValuation($customerId){
        return Receipt::where('customer_id', $customerId)->sum('total');
    }

    public static function getNumberOfProperties($customerId){
        return Property::where('sold_to', $customerId)->count();
    }
    public static function getCustomerListOfProperties($customerId){
        return Property::where('sold_to', $customerId)->orWhere('occupied_by', $customerId)->get();
    }


}
