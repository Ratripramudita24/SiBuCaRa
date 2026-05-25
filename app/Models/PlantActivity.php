<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlantActivity extends Model
{
    protected $fillable = [
        'plant_id',
        'title',
        'description',
        'planned_date',
        'is_done',
        'done_at',
        'order_index'
    ];

    public function plant()
    {
        return $this->belongsTo(Plant::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
