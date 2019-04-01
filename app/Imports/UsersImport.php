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
        if ($row[6] === 'Male')
        {
            $row[6] = 0;
        }
        else 
        {
            $row[6] = 1;
        }
        if ($row[5] === 'CNTT')
        {
            $row[5] = 1;
        }
        elseif ($row[5] === 'SPT')
        {
            $row[5] = 2;
        }
        elseif ($row[5] === 'AAA')
        {
            $row[5] = 3;
        }

        $dob = $row[2];
        $date = str_replace('/', '-', $dob);
        $dob = date('Y-m-d', strtotime($date));

        $name = $row[1];
        $split = explode(' ', $name);
        $last_name = array_pop($split);
        $count_name = strlen($name);
        $count_last = strlen($last_name);
        $count_first = $count_name - $count_last;        
        $first_name = substr($name, 0, $count_first - 1);

        return new TbUser([
            'name'=> $row[1],
            'first_name'=> $first_name,
            'last_name'=> $last_name,
            'dob'=>$dob,
            'gender' => $row[6],
            'email' => $row[4],
            'groupID' => $row[5],
            'password' => md5($row[7]),
            'description' => $row[3],
            'image'=> $row[8]
        ]);
    }
}
