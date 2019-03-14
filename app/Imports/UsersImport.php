<?php

namespace App\Imports;

use App\TbUser;
use Maatwebsite\Excel\Concerns\ToModel;

class UsersImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new TbUser([
            'name'=> $row[0],
            'gender' => $row[1],
            'email' => $row[2],
            'password' => $row[3],
            'image' => $row[5],
            'description' => $row[6],

        ]);
    }
}
