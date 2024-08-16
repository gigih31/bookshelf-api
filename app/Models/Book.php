<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'isbn',
        'title',
        'subtitle',
        'author',
        'published',
        'publisher',
        'pages',
        'description',
        'website',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'published' => 'datetime',
    ];

    /**
     * Get the user that owns the book.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
