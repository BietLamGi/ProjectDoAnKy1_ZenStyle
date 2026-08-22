<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    protected $table = 'LeaveRequest';

    protected $primaryKey = 'LeaveRequestID';

    public $timestamps = false;

    protected $fillable = [
        'UserID',
        'LeaveStartDate',
        'LeaveEndDate',
        'Reason',
        'Status',
        'CreatedAt',
        'UpdatedAt',
    ];

    protected $casts = [
        'LeaveStartDate' => 'date',
        'LeaveEndDate' => 'date',
        'CreatedAt' => 'datetime',
        'UpdatedAt' => 'datetime',
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