<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LibraryBook extends Model
{
    protected $fillable = [
        'school_id', 'title', 'author', 'isbn',
        'publisher', 'rack_number', 'quantity',
    ];

    public function issues()
    {
        return $this->hasMany(LibraryIssue::class, 'library_book_id');
    }
}
