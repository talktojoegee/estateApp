<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Wallpaper extends Model
{
    use HasFactory;

    protected $fillable = [
      'wallpaper_name','text_color','caption_color','uploaded_by', 'custom', 'filename'
    ];

    public function uploadWallpaper(Request $request){
        if($request->hasFile('attachment')){
            $extension = $request->attachment->getClientOriginalExtension();
            $size = $request->attachment->getSize();
            $filename = uniqid() . '_' . time() . '_' . date('Ymd') . '.' . $extension;
            $dir = 'assets/drive/wallpaper/';
            $request->attachment->move(public_path($dir), $filename);

            $paper = new Wallpaper();
            $paper->filename = $filename;
            $paper->wallpaper_name = $request->wallpaper_name ?? 'Unknown_'.rand(99,9999);
            $paper->text_color = $request->textColor ?? '#ffffff';
            $paper->caption_color = $request->captionColor ?? '#ffffff';
            $paper->uploaded_by = $request->custom == 1 ? Auth::user()->id : null;
            $paper->custom = $request->custom == 1 ? 1 : 0;
            $paper->save();
        }

    }

    public function getAllWallpapers(){
        return Wallpaper::where('custom', 0)->orWhere('uploaded_by', Auth::user()->id)->orderBy('wallpaper_name', 'ASC')->get();
    }
}
