<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Image;
use File;

class PropertyGallery extends Model
{
    use HasFactory;


    public function uploadPropertyGalleryImages(Request $request, $propertyId){
        $logoPath = '/assets/drive/logo/logo.png';
        $watermark = Image::make(public_path($logoPath ));

        #Property Gallery
        if($request->hasFile('gallery'))
        {
            foreach($request->file('gallery') as $file)
            {

                $image = Image::make($file);
                $extension = $file->getClientOriginalExtension();
                $dir = 'assets/drive/property/';
                $gallery_name = '_' . uniqid() . '_' . time() . '_' . date('Ymd') . '.' . $extension;
                $image->insert($watermark->resize(321,162)->opacity(50), 'center');
                $image->save(public_path($dir.$gallery_name));
                $gallery = new PropertyGallery();
                $gallery->attachment = $gallery_name;
                $gallery->property_id = $propertyId;
                $gallery->save();
            }
        }
    }

    public function deleteImage($imageId, $propertyId){
        $image = PropertyGallery::where('id', $imageId)->where('property_id', $propertyId)->first();
        $path = 'assets/drive/property/';
        if(!empty($image)){
            $imagePath = $path.$image->attachment;
            if(File::exists($imagePath)){
                File::delete($imagePath);
            }
            $image->delete();
            return true;
        }else{
            return false;
        }
    }
}
