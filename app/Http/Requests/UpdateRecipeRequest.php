<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * T5 — Server-side Validation (M5)
 * Form Request for updating an existing recipe.
 */
class UpdateRecipeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
<<<<<<< HEAD
            'title'        => 'sometimes|required|string|max:255',
            'ingredients'  => 'sometimes|required|string|max:5000',
            'instructions' => 'sometimes|required|string|max:10000',
=======
            'title'        => 'required|string|max:255',
            'ingredients'  => 'required|string|max:5000',
            'instructions' => 'required|string|max:10000',
>>>>>>> e1b21b8101c145ef6af786483709267652d41b6a
            'description'  => 'nullable|string|max:1000',
            'calories'     => 'nullable|numeric|min:0|max:99999',
            'protein'      => 'nullable|numeric|min:0|max:9999',
            'carbs'        => 'nullable|numeric|min:0|max:9999',
            'fats'         => 'nullable|numeric|min:0|max:9999',
            'image_path'   => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'        => 'Recipe title is required.',
            'title.max'             => 'Title cannot exceed 255 characters.',
            'ingredients.required'  => 'Please list the ingredients.',
            'ingredients.max'       => 'Ingredients text is too long (max 5000 characters).',
            'instructions.required' => 'Please provide the cooking instructions.',
            'instructions.max'      => 'Instructions text is too long (max 10000 characters).',
            'description.max'       => 'Description cannot exceed 1000 characters.',
            'calories.numeric'      => 'Calories must be a number.',
            'calories.min'          => 'Calories cannot be negative.',
            'calories.max'          => 'Calories value is too large.',
            'protein.numeric'       => 'Protein must be a number.',
            'protein.min'           => 'Protein cannot be negative.',
            'carbs.numeric'         => 'Carbs must be a number.',
            'carbs.min'             => 'Carbs cannot be negative.',
            'fats.numeric'          => 'Fats must be a number.',
            'fats.min'              => 'Fats cannot be negative.',
<<<<<<< HEAD
            'image_path.max'        => 'Image path is too long (max 500 characters).',
=======
            'image_path.max'        => 'Image URL is too long (max 500 characters).',
>>>>>>> e1b21b8101c145ef6af786483709267652d41b6a
        ];
    }
}
