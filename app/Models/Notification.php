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
        'status',
        'recipient_role',
        'recipient_user_id'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public function activity()
    {
        return $this->belongsTo(PlantActivity::class, 'plant_activity_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_user_id');
    }
}
