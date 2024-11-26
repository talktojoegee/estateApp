<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadPartner extends Model
{
    use HasFactory;



    public static function addPartner($leadId, $name, $email, $mobileNo, $address,
                                      $kinName, $kinMobileNo, $kinEmail, $kinAddress){
        $partner = new LeadPartner();
        $partner->lead_id = $leadId;
        $partner->full_name = $name;
        $partner->email = $email;
        $partner->mobile_no = $mobileNo;
        $partner->address = $address;

        $partner->kin_full_name = $kinName;
        $partner->kin_mobile_no = $kinMobileNo;
        $partner->kin_email = $kinEmail;
        $partner->kin_address = $kinAddress;
        $partner->save();
    }

    public static function editPartner($partnerId, $name, $email, $mobileNo, $address,
                                      $kinName, $kinMobileNo, $kinEmail, $kinAddress){
        $partner =  LeadPartner::find($partnerId);
        $partner->full_name = $name;
        $partner->email = $email;
        $partner->mobile_no = $mobileNo;
        $partner->address = $address;

        $partner->kin_full_name = $kinName;
        $partner->kin_mobile_no = $kinMobileNo;
        $partner->kin_email = $kinEmail;
        $partner->kin_address = $kinAddress;
        $partner->save();
    }



}
