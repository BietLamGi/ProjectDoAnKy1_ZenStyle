<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    //
     protected $table = 'notifications';

    protected $fillable = [
        'title',
        'message',
        'type',
        'appointment_id',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id', 'AppointmentID');
    }
}
