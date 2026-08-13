<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'Service';

    protected $primaryKey = 'ServiceID';

    public $timestamps = false;    
     protected $fillable = [
        'ServiceType',
        'Category',
        'ServiceName',
        'Description',
        'DurationMinutes',
        'Price',
        'IsActive',
        'Image',
    ];

    public function appointmentServices()
    {
        return $this->hasMany(AppointmentService::class, 'ServiceID', 'ServiceID');
    }
}