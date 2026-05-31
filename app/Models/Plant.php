<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plant extends Model
{
    protected $fillable = [
        'owner_id',
        'name',
        'variety',
        'plant_date',
        'status',
    ];

    protected $casts = [
        'plant_date' => 'date',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function recommendations()
    {
        return $this->hasMany(Recommendation::class);
    }
}

