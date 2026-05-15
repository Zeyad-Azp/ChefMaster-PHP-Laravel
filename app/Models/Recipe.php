<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $table = 'my_recipes'; //this is the table in the DB

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'ingredients',
        'instructions',
        'calories',
        'protein',
        'carbs',
        'fats',
        'image_path',
        'source_type',
        'api_recipe_id',
        'is_favorite',
    ];

    protected $casts = [
        'is_favorite' => 'boolean',
        'calories'    => 'float',
        'protein'     => 'float',
        'carbs'       => 'float',
        'fats'        => 'float',
    ];

    //Relationship: A recipe belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Data Formatting Helper: Formats calories to a specified decimal precision.
     */
    public function formatCalories(int $decimals = 1): string
    {
        return number_format((float) $this->calories, $decimals, '.', '');
    }
}
