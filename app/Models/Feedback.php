<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    //
    protected $table = 'feedback';

    protected $fillable = [
        'customer_id',
        'appointment_id',
        'rating',
        'comment',
        'status',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'CustomerID');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id', 'AppointmentID');
    }
}
