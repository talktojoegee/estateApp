<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PropertyReservation extends Model
{
    use HasFactory;

    public function getProperty(){
        return $this->belongsTo(Property::class, 'property_id');
    }
    public function getCustomer(){
        return $this->belongsTo(Lead::class, 'reserved_for');
    }
    public function getReservedBy(){
        return $this->belongsTo(User::class, 'reserved_by');
    }
    public function getActionedBy(){
        return $this->belongsTo(User::class, 'actioned_by');
    }

    public function getReceipt(){
        return $this->belongsTo(Receipt::class, 'receipt_id');
    }

    public function addReservation(Request $request,$propertyId){
        $reservation = new PropertyReservation();
        $reservation->reserved_for = $request->lead;
        $reservation->reserved_by = Auth::user()->id;
        $reservation->property_id = $propertyId;
        $reservation->note = $request->note ?? '';
        $reservation->save();
        return $reservation;
    }


    public function getAllReservationRequests(){
        return PropertyReservation::orderBy('id', 'DESC')->get();
    }




}
