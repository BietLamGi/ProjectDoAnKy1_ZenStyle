<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkSchedule extends Model
{
    protected $table = 'WorkSchedule';
    protected $primaryKey = 'ScheduleID';

    public $timestamps = false;

    protected $fillable = [
        'UserID',
        'WorkDate',
        'ShiftStart',
        'ShiftEnd',
        'ActualCheckIn',
        'ActualCheckOut',
        'Status',
    ];

    protected $casts = [
        'WorkDate' => 'date',
        'ActualCheckIn' => 'datetime',
        'ActualCheckOut' => 'datetime',
        'WorkedHours' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'UserID',
            'UserID'
        );
    }
}