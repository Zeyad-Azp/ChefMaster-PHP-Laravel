<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Recipe;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Validator;

class ChefMasterModelTest extends TestCase
{
    /**
     * 1. Relationship Test (Isolated)
     * Verifies that the User model defines a One-to-Many relationship with Recipe.
     */
    public function test_user_has_many_recipes_relationship()
    {
        $user = new User();
        
        // Assert that the recipes() method returns a HasMany relationship instance
        $this->assertInstanceOf(HasMany::class, $user->recipes());
        
        // Assert that the relationship is mapped to the correct related model
        $this->assertInstanceOf(Recipe::class, $user->recipes()->getRelated());
    }

    /**
     * 2. Model Attribute Casting
     * Tests that the is_favorite attribute is cast to boolean and macros to floats.
     */
    public function test_recipe_attributes_are_cast_correctly()
    {
        $recipe = new Recipe([
            'is_favorite' => 1,
            'calories' => '250',
            'protein' => '25.5',
            'carbs' => '10',
            'fats' => '5.2',
        ]);

        // Verify boolean casting
        $this->assertIsBool($recipe->is_favorite);
        $this->assertTrue($recipe->is_favorite);

        // Verify float casting for macros
        $this->assertIsFloat($recipe->calories);
        $this->assertEquals(250.0, $recipe->calories);
        
        $this->assertIsFloat($recipe->protein);
        $this->assertEquals(25.5, $recipe->protein);
    }

    /**
     * 3. Data Formatting Helper
     * Tests the formatCalories() helper logic inside the Recipe model.
     */
    public function test_recipe_formats_calories_correctly()
    {
        $recipe = new Recipe([
            'calories' => 314.159,
        ]);

        // Default should be 1 decimal place
        $this->assertEquals('314.2', $recipe->formatCalories());
        
        // Passing 2 decimal places
        $this->assertEquals('314.16', $recipe->formatCalories(2));
        
        // Passing 0 decimal places
        $this->assertEquals('314', $recipe->formatCalories(0));
    }

    /**
     * 4. Validation Logic (Isolated)
     * Tests validation rules in isolation without hitting the database.
     */
    public function test_recipe_validation_rules_are_enforced()
    {
        // Define the rules (simulating what would be in a FormRequest or Controller)
        $rules = [
            'title' => 'required|string|max:255',
            'source_type' => 'required|in:manual,api',
        ];

        // Scenario A: Fails when title is missing and source_type is invalid
        $invalidData = [
            'title' => '',
            'source_type' => 'external',
        ];

        $invalidValidator = Validator::make($invalidData, $rules);

        $this->assertTrue($invalidValidator->fails());
        $this->assertArrayHasKey('title', $invalidValidator->errors()->toArray());
        $this->assertArrayHasKey('source_type', $invalidValidator->errors()->toArray());

        // Scenario B: Passes when data is correct
        $validData = [
            'title' => 'Healthy Chicken Salad',
            'source_type' => 'manual',
        ];

        $validValidator = Validator::make($validData, $rules);

        $this->assertFalse($validValidator->fails());
    }
}
