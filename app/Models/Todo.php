<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\JsonResponse;

class Todo extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description'];

    function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }
}
