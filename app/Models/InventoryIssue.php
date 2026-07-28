<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryIssue extends Model
{
    protected $fillable = [
        'school_id', 'inventory_item_id', 'user_id', 'issued_by', 
        'quantity', 'issue_date', 'return_date', 'notes', 'status'
    ];

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function issuer()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }
}
