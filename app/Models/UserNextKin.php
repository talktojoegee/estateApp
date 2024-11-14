<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class UserNextKin extends Model //this is for user model
{
    use HasFactory;


    public function addUserNextKin(Request $request, $userId){
        $record = new UserNextKin();
        $record->user_id = $userId;
        $record->title = $request->nextTitle ?? null;
        $record->first_name = $request->nextFirstName ?? null;
        $record->surname = $request->nextSurname ?? null;
        $record->middle_name = $request->nextMiddleName ?? null;
        $record->mobile_no = $request->nextMobileNo ?? null;
        $record->relationship = $request->relationship ?? null;
        $record->occupation = $request->nextOccupation ?? null;
        $record->mothers_maiden_name = $request->mothersMaidenName ?? null;
        $record->means_of_id = $request->meansOfID ?? null;
        $record->office_address = $request->officeAddress ?? null;
        $record->home_address = $request->nextPermanentHomeAddress ?? null;
        $record->save();
    }
}
