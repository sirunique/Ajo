<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
   Protected  $fillable = [
       'name','email','password','phone','gender',
       'address','verify','email_verified_at','email_verified_token'
   ];

   Protected $hidden = [
    'password', 'remember_token'
   ];

   public function groups(){
       return $this->hasMany(Group::class);
   }

   public function member(){
       return $this->hasMany(Member::class);
   }


}
