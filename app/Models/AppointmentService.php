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

public function service()
{
    return $this->belongsTo(
        Service::class,
        'ServiceID',
        'ServiceID'
    );
}
}

