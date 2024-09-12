<?php

namespace App\Imports;

use App\Models\LeadBulkImportDetail;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStartRow;

class BulkImportLead implements ToModel, WithStartRow, WithMultipleSheets
{
    public $masterId, $header;

    public function __construct($header, $masterId){
        $this->masterId = $masterId;
        $this->header = $header;
    }
    /**
     * @return int
     */
    public function startRow(): int
    {
        if(isset($this->header)){
            return 2;
        }
        return 1;

    }
    public function sheets(): array
    {
        return [
            0 => $this,
            //1 => new SecondSheetImport(),
        ];
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new LeadBulkImportDetail([
            "master_id"=>$this->masterId,
            "entry_date"=> str_replace("/", "-", $row[0]),
            "first_name"=>$row[1],
            "last_name"=>$row[2] ?? 'N/A',
            "phone"=>$row[3] ?? '234',
            "email"=>$row[4] ?? 'placeholder@email.com',
            "gender"=>$row[5] ?? 1,
            "address"=>$row[6] ?? 'N/A',
            "dob"=> null,
            "source_id"=> 1,
            "slug"=> 'slug',
        ]);
    }
}
