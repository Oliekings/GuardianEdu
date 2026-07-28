<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    protected $fillable = ['school_id', 'inventory_category_id', 'name', 'code', 'unit', 'description'];

    public function category()
    {
        return $this->belongsTo(InventoryCategory::class, 'inventory_category_id');
    }

    public function issues()
    {
        return $this->hasMany(InventoryIssue::class);
    }
}
