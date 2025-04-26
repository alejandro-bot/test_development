<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Cocktail extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'category',
        'glass',
        'type',
        'instructions'
    ];

    /**
     * Los ingredientes que pertenecen a este cóctel
     */
    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class)
            ->withPivot('measure')
            ->withTimestamps();
    }

    /**
     * Los usuarios que han guardado este cóctel
     */
    public function savedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'saved_cocktails')
            ->withTimestamps();
    }
}
