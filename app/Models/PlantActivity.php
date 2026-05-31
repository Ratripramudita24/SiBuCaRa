<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlantActivity extends Model
{
    protected $fillable = [
        'plant_id',
        'assigned_user_id',
        'title',
        'description',
        'planned_date',
        'status',
        'is_done',
        'done_at',
        'order_index',
        'notes',
        'system_notes'
    ];

    protected $casts = [
        'planned_date' => 'date',
        'done_at' => 'datetime',
    ];

    public function plant()
    {
        return $this->belongsTo(Plant::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
