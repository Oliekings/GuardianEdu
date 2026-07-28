<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsSector extends Model
{
    protected $fillable = ['school_id', 'key', 'content', 'is_visible'];

    protected $casts = [
        'content' => 'json',
        'is_visible' => 'boolean',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
