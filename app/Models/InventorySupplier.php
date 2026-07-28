<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventorySupplier extends Model
{
    protected $fillable = ['school_id', 'name', 'phone', 'email', 'address', 'contact_person'];
}
