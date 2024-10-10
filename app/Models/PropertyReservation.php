<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PropertyReservation extends Model
{
    use HasFactory;

    public function addReservation(Request $request,$propertyId){
        $reservation = new PropertyReservation();
        $reservation->reserved_for = $request->customer;
        $reservation->reserved_by = Auth::user()->id;
        $reservation->property_id = $propertyId;
        $reservation->note = $request->note ?? '';
        $reservation->save();
    }


    public function getAllReservationRequests(){
        return PropertyReservation::orderBy('id', 'DESC')->get();
    }




}
