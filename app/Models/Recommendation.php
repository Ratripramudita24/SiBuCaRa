<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model
{
    protected $fillable = [
        'plant_id',
        'penyuluh_id',
        'recommendation_text',
    ];

    public function plant()
    {
        return $this->belongsTo(Plant::class);
    }

    public function penyuluh()
    {
        return $this->belongsTo(User::class, 'penyuluh_id');
    }
}
