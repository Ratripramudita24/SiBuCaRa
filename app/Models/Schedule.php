<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'plant_id',
        'activity_name',
        'target_date',
        'status',
        'notes',
        'reason_not_done',
    ];

    protected $casts = [
        'target_date' => 'date',
    ];

    public function plant()
    {
        return $this->belongsTo(Plant::class);
    }
}
