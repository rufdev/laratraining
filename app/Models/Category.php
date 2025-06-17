<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator; 
class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the assets for the category.
     * Defines a one-to-many relationship: one category has many assets.
     */
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }

}