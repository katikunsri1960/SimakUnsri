<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportDPNA implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new NilaiKomponenEvaluasi([
            'name' => $row['name'],
            'email' => $row['email'],
            'password' => bcrypt($row['password']),
        ]);
    }
}
