<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [
        'admin_id','name','description',
        'max_capacity','searchable','group_link','amount','status'
    ];

    public static function check_group_link($group_link){
        // $check = Group::where('group_link', $group_link)->get();
        // return $check;
        return Group::where('group_link', $group_link)->get();
    }
    public static function genrate(){
        // $random1 = mt_rand(345, 999);
        // $random = mt_rand(178, 789);
        // $code = 'cash_'.$random1.''.$random;
        // return $code;

        $permitted_chars = '0123456343534535789abcdefghij353453535353535klmnopqrst43534543uvwxyz';
        return substr(str_shuffle($permitted_chars), 0, 10);
    }

    
}
