<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}

