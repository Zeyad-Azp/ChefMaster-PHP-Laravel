<?php

namespace Tests\Feature;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Regression tests for previously-fixed recipe bugs.
 *
 * Covers:
 *  - Bug  1: image_path accepts relative paths like /storage/uploads/x.jpg
 *  - Bug 15: Recipe::is_favorite is cast to boolean and macros are cast to float
 *  - Bug 24: Recipe-creation UI is hidden from guests on the home page
 */
class RecipeRegressionTest extends TestCase
{
    use RefreshDatabase;

    private function authedUser(): User
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        return $user;
    }

    /** Bug 1 — relative image_path must pass validation on store. */
    public function test_store_accepts_relative_image_path(): void
    {
        $this->authedUser();

        $response = $this->postJson('/recipes', [
            'title'        => 'Pasta Bake',
            'ingredients'  => 'Pasta, sauce, cheese',
            'instructions' => 'Mix and bake.',
            'image_path'   => '/storage/uploads/pasta.jpg',
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.image_path', '/storage/uploads/pasta.jpg');

        $this->assertDatabaseHas('my_recipes', [
            'title'      => 'Pasta Bake',
            'image_path' => '/storage/uploads/pasta.jpg',
        ]);
    }

    /** Bug 1 — relative image_path must pass validation on update. */
    public function test_update_accepts_relative_image_path(): void
    {
        $user = $this->authedUser();

        $recipe = Recipe::create([
            'user_id'      => $user->id,
            'title'        => 'Old Title',
            'ingredients'  => 'a',
            'instructions' => 'b',
            'source_type'  => 'manual',
        ]);

        $response = $this->putJson("/recipes/{$recipe->id}", [
            'title'        => 'New Title',
            'ingredients'  => 'a',
            'instructions' => 'b',
            'image_path'   => '/storage/uploads/new.webp',
        ]);

        $response->assertOk()->assertJsonPath('success', true);

        $this->assertDatabaseHas('my_recipes', [
            'id'         => $recipe->id,
            'title'      => 'New Title',
            'image_path' => '/storage/uploads/new.webp',
        ]);
    }

    /** Bug 1 — junk that exceeds the max length must still fail. */
    public function test_store_rejects_image_path_longer_than_500_chars(): void
    {
        $this->authedUser();

        $response = $this->postJson('/recipes', [
            'title'        => 'X',
            'ingredients'  => 'a',
            'instructions' => 'b',
            'image_path'   => '/storage/uploads/' . str_repeat('a', 600) . '.jpg',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['image_path']);
    }

    /** Bug 15 — is_favorite is a real boolean, macros are floats. */
    public function test_recipe_casts_are_applied(): void
    {
        $user = $this->authedUser();

        $recipe = Recipe::create([
            'user_id'      => $user->id,
            'title'        => 'Casts',
            'ingredients'  => 'a',
            'instructions' => 'b',
            'calories'     => '250.5',
            'protein'      => '12',
            'carbs'        => '30.25',
            'fats'         => '8.0',
            'is_favorite'  => 1,
            'source_type'  => 'manual',
        ])->fresh();

        $this->assertIsBool($recipe->is_favorite);
        $this->assertTrue($recipe->is_favorite);
        $this->assertIsFloat($recipe->calories);
        $this->assertSame(250.5, $recipe->calories);
        $this->assertIsFloat($recipe->protein);
        $this->assertIsFloat($recipe->carbs);
        $this->assertIsFloat($recipe->fats);
    }

    /** Bug 15 — toggleFavorite returns is_favorite as a JSON boolean. */
    public function test_toggle_favorite_returns_boolean_in_json(): void
    {
        $user = $this->authedUser();

        $recipe = Recipe::create([
            'user_id'      => $user->id,
            'title'        => 'Fav',
            'ingredients'  => 'a',
            'instructions' => 'b',
            'is_favorite'  => 0,
            'source_type'  => 'manual',
        ]);

        $response = $this->patchJson("/recipes/{$recipe->id}/favorite");

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('is_favorite', true);
    }

    /** Bug 24 — guests must not see the "Add Recipe" / "Create New Recipe" triggers on /. */
    public function test_guest_does_not_see_add_recipe_ui_on_home(): void
    {
        $response = $this->get('/');

        $response->assertOk()
            ->assertDontSee('openAddRecipeModal', false)
            ->assertDontSee('Create New Recipe', false)
            ->assertDontSee('id="fabAdd"', false)
            ->assertSee('Login to Create Recipes', false);
    }

    /** Bug 24 — authenticated users must still see the recipe-creation triggers. */
    public function test_authenticated_user_sees_add_recipe_ui_on_home(): void
    {
        $this->authedUser();

        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('openAddRecipeModal', false)
            ->assertSee('Create New Recipe', false)
            ->assertSee('id="fabAdd"', false)
            ->assertDontSee('Login to Create Recipes', false);
    }
}
