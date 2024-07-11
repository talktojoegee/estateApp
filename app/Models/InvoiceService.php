<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InvoiceService extends Model
{
    use HasFactory;
    protected $primaryKey = 'is_id';

    public function addService(Request $request){
        $record = new InvoiceService();
        $record->is_name = $request->serviceName ?? '';
        $record->is_slug = Str::slug($request->serviceName);
        $record->save();
        return $record;
    }
    public function editService(Request $request){
        $record =  InvoiceService::find($request->serviceId);
        $record->is_name = $request->serviceName ?? '';
        $record->is_slug = Str::slug($request->serviceName);
        $record->save();
        return $record;
    }


    public function getServices(){
        return InvoiceService::all();
    }

}
