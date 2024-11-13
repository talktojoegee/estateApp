<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    public function getCategory(){
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }


    public function addProduct(Request $request){
        $product = new Product();
        $product->org_id = Auth::user()->org_id;
        $product->added_by = Auth::user()->id;
        $product->category_id = $request->productCategory;
        $product->product_name = $request->productName;
        $product->slug = Str::slug($request->productName).'-'.Str::random(5);
        $product->cost = $request->cost ?? 0;
        $product->price = $request->price ?? 0;
        $product->sku = $request->sku ?? null ;
        $product->low_inventory_notice = $request->lowInventoryNotice ?? 0;
        $product->stock = $request->stock ?? 0;
        $product->photo = $this->uploadProductPhoto($request->photo);
        $product->description = $request->description ?? null;
        $product->added_by = Auth::user()->id ?? null;
        $product->save();
    }

    public function editProduct(Request $request){
        $product =  Product::find($request->productId);
        $product->org_id = Auth::user()->org_id;
        $product->added_by = Auth::user()->id;
        $product->category_id = $request->productCategory;
        $product->product_name = $request->productName;
        $product->slug = Str::slug($request->productName).'-'.Str::random(5);
        $product->cost = $request->cost ?? 0;
        $product->price = $request->price ?? 0;
        $product->sku = $request->sku ?? null ;
        $product->low_inventory_notice = $request->lowInventoryNotice ?? 0;
        $product->stock = $request->stock ?? 0;
        if(isset($request->photo)) {
            $product->photo = $this->uploadProductPhoto($request->photo);
        }
        $product->description = $request->description ?? null;
        $product->save();
    }

    public function uploadProductPhoto($avatarHandler){
        return $avatarHandler->store('products', 'public');
    }
    public function deleteFile($file){
        if(\File::exists(public_path('storage/'.$file))){
            \File::delete(public_path('storage/'.$file));
        }
    }

    public function getAllOrgProducts(){
        return Product::where('org_id', Auth::user()->org_id)->orderBy('id', 'DESC')->get();
    }
}
