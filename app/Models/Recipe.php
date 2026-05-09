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

    //Relationship: A recipe belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
