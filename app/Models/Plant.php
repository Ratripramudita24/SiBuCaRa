<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plant extends Model
{
    protected $fillable = [
        'name','start_date','location','harvest_date','status','owner_id','variety_id'
    ];

    /**
     * Get the owner of the plant
     */
    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the variety of the plant
     */
    public function variety()
    {
        return $this->belongsTo(Variety::class);
    }

    public function activities()
    {
        return $this->hasMany(PlantActivity::class);
    }
}
