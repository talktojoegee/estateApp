<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PropertyBulkImportMaster extends Model
{
    use HasFactory;

    public function getImportedBy(){
        return $this->belongsTo(User::class, "imported_by");
    }

    public function getBulkImportDetails(){
        return $this->hasMany(PropertyBulkImportDetail::class, "master_id");
    }

    public function publishBulkImport(Request $request, $userId){
        $bulk = new PropertyBulkImportMaster();
        $bulk->imported_by = $userId;
        $bulk->batch_code = substr(sha1(time()),21,40);
        $bulk->narration = $request->narration ?? '';
        $bulk->attachment =  $this->uploadFile($request) ?? '';
        $bulk->save();
        return $bulk;

    }

    public function uploadFile(Request $request)
    {
        if ($request->hasFile('attachment')) {
            $file = $request->attachment;
            $extension = $file->getClientOriginalExtension();
            $filename = uniqid() . '_' . time() . '_' . date('Ymd') . '.' . $extension;
            $dir = 'assets/drive/import/';
            $file->move(public_path($dir), $filename);
            return $filename;

        }
    }


    public function getAllRecords(){
        return PropertyBulkImportMaster::orderBy('id', 'DESC')->get();
    }

    public function getRecordByBatchCode($batchCode){
        return PropertyBulkImportMaster::where('batch_code', $batchCode)->first();
    }

}
