<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TbUser extends Model
{
	use SoftDeletes;
    protected $table="tb_users";
    protected $fillable = [
        'name','dob','first_name','last_name','groupID','gender','email','password','email','description','image'
    ];
    protected $dates = ['deleted_at'];
}
