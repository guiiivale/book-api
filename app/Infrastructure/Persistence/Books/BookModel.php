<?php

namespace App\Infrastructure\Persistence\Books;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookModel extends Model
{
    protected $table = 'books';

    protected $fillable = [
        'user_id',
        'name',
        'isbn',
        'value',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
