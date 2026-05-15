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

<<<<<<< HEAD
    protected $casts = [
        'is_favorite' => 'boolean',
        'calories'    => 'float',
        'protein'     => 'float',
        'carbs'       => 'float',
        'fats'        => 'float',
    ];

=======
>>>>>>> e1b21b8101c145ef6af786483709267652d41b6a
    //Relationship: A recipe belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
<<<<<<< HEAD

    /**
     * Data Formatting Helper: Formats calories to a specified decimal precision.
     */
    public function formatCalories(int $decimals = 1): string
    {
        return number_format((float) $this->calories, $decimals, '.', '');
    }
=======
>>>>>>> e1b21b8101c145ef6af786483709267652d41b6a
}
