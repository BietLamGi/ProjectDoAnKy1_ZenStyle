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
        // WorkedHours is a COMPUTED column on the SQL Server side (derived
        // from ActualCheckIn/ActualCheckOut) - intentionally NOT in
        // $fillable. Never assign it via create()/update(); SQL Server
        // rejects the write. It's still cast here so reading it back out
        // (e.g. in views) returns a float.
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