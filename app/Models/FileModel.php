<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class FileModel extends Model
{
    use HasFactory;
    public function uploadFiles(Request $request)
    {
        if ($request->hasFile('attachments')) {
            foreach($request->attachments as $attachment){
                $extension = $attachment->getClientOriginalExtension();
                $size = $attachment->getSize();
                $filename = uniqid() . '_' . time() . '_' . date('Ymd') . '.' . $extension;
                $dir = 'assets/drive/cloud/';
                $attachment->move(public_path($dir), $filename);
                $file = new FileModel();

                $file->property_id = $request->property ?? null ;

                $file->filename = $filename;
                $file->name = $request->fileName;
                $file->folder_id = $request->folder;
                $file->calendar_id = $request->calendarId ?? null;
                $file->uploaded_by = Auth::user()->id;
                $file->slug = substr(sha1(time()),32,40);
                $file->size = $size;
                $file->client_id = $request->client ?? null;
                $file->lead_id = $request->lead ?? null;
                $file->type = isset($request->lead) ? 1 : 0;
                $file->org_id = Auth::user()->org_id;
                $file->save();
            }
        }

    }

    public function uploadSingleFile(Request $request)
    {
        if ($request->hasFile('attachment')) {
                $extension = $request->attachment->getClientOriginalExtension();
                $size = $request->attachment->getSize();
                $filename = uniqid() . '_' . time() . '_' . date('Ymd') . '.' . $extension;
                $dir = 'assets/drive/cloud/';
                $request->attachment->move(public_path($dir), $filename);
                $file = new FileModel();
                $file->filename = $filename;
                $file->name = $request->fileName ?? 'Offer Letter';
                $file->folder_id = $request->folder ?? 0;
                $file->calendar_id = $request->calendarId ?? null;
                $file->uploaded_by = Auth::user()->id;
                $file->slug = substr(sha1(time()),32,40);
                $file->size = $size;
                $file->client_id = $request->client ?? null;
                $file->lead_id = $request->lead ?? null;
                $file->type = isset($request->lead) ? 1 : 0;
                $file->org_id = Auth::user()->org_id;
                $file->save();
                return $file;

        }

    }



    public function getUploadedBy(){
        return $this->belongsTo(User::class, 'uploaded_by');
    }


    public function getFilesByFolderId($id){
        return FileModel::where('folder_id', $id)->where('org_id', Auth::user()->org_id)->get();
    }

    public function getFileById($id){
        return FileModel::find($id);
    }

    public function getIndexFiles(){
        return FileModel::where('folder_id',0)->where('type', 0)->where('org_id', Auth::user()->org_id)->get();
    }

    public function getCustomerFiles($customerId){
        return FileModel::where('lead_id', $customerId)->get();
    }

    public function downloadFile($file_name) {
        $file_path = public_path('assets/drive/cloud/'.$file_name);
        if(file_exists($file_path)){
            return response()->download($file_path);
        }else{
            return 0; //file not found.
        }
    }

    public function deleteFile($file){
        if(\File::exists(public_path('assets/drive/cloud/'.$file))){
            \File::delete(public_path('assets/drive/cloud/'.$file));
            return 1;
        }else{
            return 0;
        }
    }

    public function moveDependentFilesToIndex($parentId){
        $files = FileModel::where('folder_id', $parentId)->get();
        foreach($files as $file){
            $file->folder_id = 0;
            $file->save();
        }
    }

    public function getClientDocuments($clientId, $orgId){
        return FileModel::where('client_id', $clientId)->where('org_id', $orgId)->orderBy('id', 'DESC')->get();
    }

    public function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
    }
}
