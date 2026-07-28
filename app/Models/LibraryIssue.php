<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LibraryIssue extends Model
{
    protected $fillable = [
        'school_id', 'library_book_id', 'library_member_id', 
        'issue_date', 'due_date', 'return_date', 'status'
    ];

    public function book()
    {
        return $this->belongsTo(LibraryBook::class, 'library_book_id');
    }

    public function member()
    {
        return $this->belongsTo(LibraryMember::class, 'library_member_id');
    }
}
