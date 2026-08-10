<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Service;

class AppointmentService extends Model
{
   protected $table = 'AppointmentService';

protected $primaryKey = 'AppointmentServiceID';

public $timestamps = false;

protected $fillable = [
    'AppointmentID',
    'ServiceID',
    'Quantity',
    'UnitPrice',
];
// reception
public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'AppointmentID', 'AppointmentID');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'ServiceID', 'ServiceID');
    }
}

