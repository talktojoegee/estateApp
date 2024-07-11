<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AssignFrequency extends Model
{
    use HasFactory;


    public function getCompany(){
        return $this->belongsTo(Organization::class, 'org_id');
    }



    public function getCategory(){
        return $this->belongsTo(LicenceCategory::class, 'category');
    }

    public function getPost(){
        return $this->belongsTo(Post::class, 'post_id');
    }



    public function getAssignedBy(){
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function getWorkstation(){
        return $this->belongsTo(Workstation::class, 'station_id');
    }






    public static function addFrequencyDetails($operation, $postId,$detailId,$orgId,$frequency,$startDate,
                                               $expiresAt,$stationId,$mode,$category,$band,$type,
                                               $batchCode, $slug, $make, $formNo, $emission, $maxEffect,
                                               $callSign, $licenseNo, $aerialXtics,$maxFreqTolerance,
                                               $formSerialNo){
        $record = $operation == 'new' ?  new AssignFrequency() : AssignFrequency::find($detailId);
        $record->post_id = $postId;
        $record->radio_detail_id = $detailId;
        $record->org_id = $orgId;
        $record->assigned_by = Auth::user()->id;
        $record->frequency = $frequency;
        $record->status = 1; //active
        $record->start_date = $startDate;
        $record->expires_at = $expiresAt;
        $record->station_id = $stationId;
        $record->mode = $mode;
        $record->category = $category;
        $record->band = $band;
        $record->type = $type;
        $record->batch_code = $batchCode;
        $record->slug = $slug;
        $record->make = $make;
        $record->form_no = $formNo;
        $record->call_sign = $callSign;
        $record->license_no = $licenseNo;
        $record->aerial_xtics = $aerialXtics;
        $record->max_effective_rad = $maxEffect;
        $record->emission_bandwidth = $emission;
        $record->max_freq_tolerance = $maxFreqTolerance;
        $record->form_serial_no = $formSerialNo;
        $record->save();
        return $record;
    }

    public function getAllCertificates(){
        return AssignFrequency::orderBy('id', 'DESC')->get();
    }
    public function getAllCertificatesByStatus($status){
        return AssignFrequency::whereIn('status',$status)->orderBy('id', 'DESC')->get();
    }

    public function getAllOrgCertificates($orgId){
        return AssignFrequency::where('org_id', $orgId)->orderBy('id', 'DESC')->get();
    }
    public function getAllOrgCertificatesByStatus($orgId, $status){
        return AssignFrequency::where('org_id', $orgId)->whereIn('status', $status)->orderBy('id', 'DESC')->get();
    }
    public function getAllGroupedCertificates(){
        return AssignFrequency::groupBy('batch_code')->orderBy('id', 'DESC')->get();
    }

    public function getAllGroupedOrgCertificates($orgId){
        return AssignFrequency::where('org_id', $orgId)->groupBy('batch_code')->orderBy('id', 'DESC')->get();
    }

    public function getQuantityByBatchCode($batchCode){
        return AssignFrequency::where('batch_code', $batchCode)->count();
    }
    public function getCertificateByLicenseBySlug($slug){
        return AssignFrequency::where('slug', $slug)->first();
    }

    public function getAssignedFrequencyById($id){
        return AssignFrequency::find($id);
    }
    public static function getAssignedFrequenciesByPostId($postId){
        return AssignFrequency::where('post_id',$postId)->get();
    }





}
