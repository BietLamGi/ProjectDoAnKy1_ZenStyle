<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $table = 'Appointment';

    protected $primaryKey = 'AppointmentID';

    public $timestamps = false;

    protected $fillable = [
        'CustomerID',
        'StaffID',
        'AppointmentDate',
        'StartTime',
        'EndTime',
        'Status',
        'Notes',
    ];
}