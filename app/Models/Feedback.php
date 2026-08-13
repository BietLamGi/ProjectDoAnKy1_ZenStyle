<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'Feedback';
    protected $primaryKey = 'FeedbackID';

    public $timestamps = false;

    protected $fillable = [
        'AppointmentID',
        'Rating',
        'Comments',
        'FeedbackDate',
    ];

    protected $casts = [
        'FeedbackDate' => 'datetime',
        'Rating' => 'integer',
    ];

    public function appointment()
    {
        return $this->belongsTo(
            Appointment::class,
            'AppointmentID',
            'AppointmentID'
        );
    }
}