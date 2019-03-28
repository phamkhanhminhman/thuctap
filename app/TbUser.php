<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TbUser extends Model
{
	use SoftDeletes;
    protected $table="tb_users";
    protected $fillable = [
        'name', 'gender','email','password','email','description'
    ];
    protected $dates = ['deleted_at'];
}
