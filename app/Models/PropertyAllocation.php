<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PropertyAllocation extends Model
{
    use HasFactory;

    public function getEstate(){
        return $this->belongsTo(Estate::class, 'estate_id');
    }
    public function getProperty(){
        return $this->belongsTo(Property::class, 'property_id');
    }
    public function getCustomer(){
        return $this->belongsTo(Lead::class, 'customer_id');
    }
    public function getAllocatedBy(){
        return $this->belongsTo(User::class, 'allocated_by');
    }
    public function getActionedBy(){
        return $this->belongsTo(User::class, 'actioned_by');
    }

    public function getAllocationByPropertyId($propertyIds,$status){
        return PropertyAllocation::whereIn('property_id', $propertyIds)->whereIn('status',$status)->get();
    }


    public function getPropertyAllocationList(){
        return PropertyAllocation::orderBy('id', 'DESC')->get();
    }

    public function getAllocationById($allocationId){
        return PropertyAllocation::find($allocationId);
    }

    public function addAllocation($estateId, $propertyId, $customerId, $level){
        $allocation = new PropertyAllocation();
        $allocation->estate_id = $estateId;
        $allocation->property_id = $propertyId;
        $allocation->customer_id = $customerId;
        $allocation->level = $level;
        $allocation->allocated_by = Auth::user()->id;
        $allocation->allocated_at = now();
        $allocation->save();
    }

    public static function addPropertyAllocation($estateId, $propertyId, $customerId, $level, $status){
        $allocation = new PropertyAllocation();
        $allocation->estate_id = $estateId;
        $allocation->property_id = $propertyId;
        $allocation->customer_id = $customerId;
        $allocation->level = $level;
        $allocation->allocated_by = Auth::user()->id;
        $allocation->allocated_at = now();
        $allocation->status = $status;
        $allocation->save();
    }

    public function checkExistingAllocation($estateId, $property, $level){
        return PropertyAllocation::where('estate_id', $estateId)->where('property_id', $property)
            ->where('level', $level)->first();
    }
}
