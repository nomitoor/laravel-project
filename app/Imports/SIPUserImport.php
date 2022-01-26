<?php

namespace App\Imports;

use App\Models\SIPUser;
use Maatwebsite\Excel\Concerns\ToModel;

class SIPUserImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new SIPUser([
            'username' => $row[0],
            'password' => $row[0],
            'host_name' => $row[0],
            'port' => $row[0],
            'country_code' => $row[0]
        ]);
    }
}
