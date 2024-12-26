<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTransactions extends Model
{
    use HasFactory;


    public function getPurchasedBy(){
        return $this->belongsTo(User::class, 'purchased_by');
    }


    public function getItem(){
        return $this->belongsTo(InventoryTransactionDetail::class, 'item_id');
    }



    public function getSoldBy(){
        return $this->belongsTo(User::class, 'sold_by');
    }



    public function getDischargedTo(){
        return $this->belongsTo(User::class, 'vendor_id');
    }



    public function getItems(){
        return $this->hasMany(InventoryTransactionDetail::class, 'master_id');
    }



    public function getVendor(){
        return $this->belongsTo(Client::class, 'vendor_id');
    }


    public static function newInventoryTransaction($transDate, $vendorId, $authorId, $type){
        $entry = new InventoryTransactions();
        $entry->vendor_id = $vendorId;
        $entry->trans_date = $transDate;
        $entry->trans_type = $type;
        $entry->purchased_by = $type == 1 ? $authorId : null;
        $entry->sold_by = $type == 2 ? $authorId : null;
        $entry->slug = substr(sha1(time()),29,40);
        $entry->save();
        return $entry;
    }



    public static function findOneById($id){
        return InventoryTransactions::find($id);
    }


    public static function findAllByType($type){
        return InventoryTransactions::where("trans_type",$type)->orderBy('id', 'DESC')->get();
    }

    public static function findOneBySlug($slug){
        return InventoryTransactions::where("slug",$slug)->first();
    }


}
