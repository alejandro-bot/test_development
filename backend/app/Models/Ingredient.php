<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'measure'
    ];

    /**
     * Los cócteles que usan este ingrediente
     */
    public function cocktails(): BelongsToMany
    {
        return $this->belongsToMany(Cocktail::class)
            ->withPivot('measure')
            ->withTimestamps();
    }
}
