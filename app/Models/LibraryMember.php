<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LibraryMember extends Model
{
    protected $fillable = ['school_id', 'user_id', 'library_card_number'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function issues()
    {
        return $this->hasMany(LibraryIssue::class, 'library_member_id');
    }
}
