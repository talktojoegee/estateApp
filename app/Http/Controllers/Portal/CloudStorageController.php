<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\FileModel;
use App\Models\FolderModel;
use App\Models\SharedFile;
use Illuminate\Http\Request;

class CloudStorageController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        $this->file = new FileModel();
        $this->folder = new FolderModel();
        $this->sharedfile = new SharedFile();
    }

    public function showCloudStorage(){
        return view('storage.index',[
            'folders'=>$this->folder->getAllFolders(),
            'files'=>$this->file->getIndexFiles()
        ]);
    }

    public function storeFiles(Request $request){
        $this->validate($request,[
            'attachments'=>'required',
            'folder'=>'required',
            'fileName'=>'required'
        ]);
        $this->file->uploadFiles($request);
        session()->flash("success", "Your file(s) were uploaded!");
        return back();
    }

    public function createFolder(Request $request){
        $this->validate($request,[
            'folderName'=>'required',
            'visibility'=>'required'
        ]);

        $this->folder->setNewFolder($request);
        session()->flash("success", "<strong>Success!</strong> New folder created.");
        return back();
    }

    public function openFolder(Request $request){
        $folder = $this->folder->getFolderBySlug($request->slug);

        if(!empty($folder)){
            $files = $this->file->getFilesByFolderId($folder->id);
            $subFolders = $this->folder->getSubFoldersByParentId($folder->id);
            $folders = $this->folder->getAllFolders();
            return view('storage.view',
                ['files'=>$files, 'folder'=>$folder, 'subFolders'=>$subFolders, 'folders'=>$folders]);
        }else{
            session()->flash("error", " No record found.");
            return back();
        }
    }


    public function downloadAttachment(Request $request){
        try{
            //return dd($request->all());
            return $this->file->downloadFile($request->slug);
            /*session()->flash("success", "Processing request...");
            return back();*/
        }catch (\Exception $ex){
            session()->flash("error", "Whoops! File does not exist.");
            return back();
        }

    }


    public function deleteAttachment(Request $request){
        $this->validate($request,[
            'directory'=>'required',
            'key'=>'required'
        ]);
        $file = $this->file->getFileById($request->key);
        if(!empty($file)){
            #Unlink
            $this->file->deleteFile($file->filename);
            $file->delete();
            session()->flash("success", "File deleted.");
            return back();
        }else{
            session()->flash("error", "Whoops! File does not exist.");
            return back();
        }

    }
    public function renameAttachment(Request $request){
        $this->validate($request,[
            //'directory'=>'required',
            'key'=>'required',
            'newName'=>'required'
        ],[
            'newName.required'=>'Enter file name'
        ]);
        $file = $this->file->getFileById($request->key);
        if(!empty($file)){
            $file->name = $request->newName ?? 'No name';
            $file->save();
            session()->flash("success", "Changes saved!.");
            return back();
        }else{
            session()->flash("error", "Whoops! File does not exist.");
            return back();
        }

    }

    public function moveAttachment(Request $request){
        $this->validate($request,[
            'source'=>'required',
            'key'=>'required',
            'destination'=>'required'
        ],[
            'destination.required'=>'Choose destination folder',
            'source.required'=>'Choose source folder',
        ]);
        $file = $this->file->getFileById($request->key);
        if(!empty($file)){
            $file->folder_id = $request->destination ?? 0;
            $file->save();
            session()->flash("success", "File moved to the chosen directory.");
            return back();
        }else{
            session()->flash("error", "Whoops! File does not exist.");
            return back();
        }

    }


    public function deleteFolder(Request $request){
        $this->validate($request,[
            'folder_key'=>'required'
        ]);
        $folder = $this->folder->getFolderById($request->folder_key);
        if(!empty($folder)){
            $this->folder->deleteFolder($request->folder_key);
            $this->folder->moveDependentFoldersToIndex($request->folder_key);
            $this->file->moveDependentFilesToIndex($request->folder_key);
            session()->flash("success", " Folder deleted.");
            return back();
        }else{
            session()->flash("error", "Whoops! Folder does not exist.");
            return back();
        }
    }
}
