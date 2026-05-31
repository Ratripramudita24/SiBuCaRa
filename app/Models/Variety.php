<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variety extends Model
{
    protected $fillable = [
        'name',
        'description',
        'color',
    ];

    /**
     * Get the plants that use this variety
     */
    public function plants()
    {
        return $this->hasMany(Plant::class);
    }
}
