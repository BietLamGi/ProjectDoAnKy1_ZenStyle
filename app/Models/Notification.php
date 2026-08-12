<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'Notification';

    protected $primaryKey = 'NotificationID';

    public $timestamps = false;

    protected $fillable = [
        'UserID',
        'Title',
        'Message',
        'Type',
        'IsRead',
        'CreatedAt',
    ];

    protected $casts = [
        'IsRead' => 'boolean',
        'CreatedAt' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'UserID', 'UserID');
    }
}