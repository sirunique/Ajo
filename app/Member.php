<?php

namespace App;
use DB;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable =[
        'group_id','user_id','role'
    ];
    

    // public function groups(){
    //     return $this->hasMany(Group::class);
    // }

}
