<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'notes',
        'booking_date',
        'start_time',
        'end_time',
        'status',
    ];
}
