<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\AppointmentService;

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
    public function customer()
    {
        return $this->belongsTo(Customer::class, 
        'CustomerID',
         'CustomerID');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'StaffID', 'UserID');
    }

    public function services()
    {
        return $this->hasMany(AppointmentService::class, 'AppointmentID', 'AppointmentID');
    }
    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'AppointmentID', 'AppointmentID');
    }
}