<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'plant_activity_id',
        'channel',
        'message',
        'scheduled_at',
        'sent_at',
        'status'
    ];
}
