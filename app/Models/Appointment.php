<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $table = 'appointments';

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
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'CustomerID', 'CustomerID');
    }
    // reception
    public function staff()
    {
        return $this->belongsTo(User::class, 'StaffID', 'id');
    }

    public function services()
    {
        return $this->hasMany(AppointmentService::class, 'AppointmentID', 'AppointmentID');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'appointment_id', 'AppointmentID');
    }
}