<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTransactionDetail extends Model
{
    use HasFactory;





    public static function addInventoryDetails($masterId, $itemId, $quantity, $amount, $type){
        $detail = new InventoryTransactionDetail();
        $detail->master_id = $masterId;
        $detail->item_id = $itemId;
        $detail->quantity = $quantity;
        $detail->amount = $amount;
        $detail->trans_type = $type;
        $detail->save();
    }



    public function getItemLabel($id){
        return Product::find($id);
    }
}
