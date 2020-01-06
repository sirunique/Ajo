<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable =[
        'group_id','member_id','week', 'startDate', 'endDate', 'payment_status', 'payment_amount', 'date'
    ];
}
