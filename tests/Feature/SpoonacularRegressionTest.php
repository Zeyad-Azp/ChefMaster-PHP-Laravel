<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Regression tests for SpoonacularController fixes.
 *
 * Covers:
 *  - Bug  2: search?mode=ingredients passes includeIngredients (not query)
 *  - Bug  2: search defaults to mode=name and uses the query parameter
 *  - Bug 16: detail returns 502 when upstream omits id/title
 *  - Bug 20: missing SPOONACULAR_BASE_URL env still produces a valid request URL
 */
class SpoonacularRegressionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['services.spoonacular.key' => 'test-key']);
    }

    /** Bug 2 — ingredient mode must use includeIngredients + fillIngredients + ranking. */
    public function test_search_in_ingredients_mode_uses_include_ingredients(): void
    {
        Http::fake([
            '*complexSearch*' => Http::response(['results' => []], 200),
        ]);

        $response = $this->get('/api/spoonacular/search?query=tomato,basil&mode=ingredients');

        $response->assertOk()->assertJsonPath('success', true);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), '/recipes/complexSearch')
                && $request['includeIngredients'] === 'tomato,basil'
                && (string) $request['fillIngredients'] === 'true'
                && (string) $request['ranking'] === '1'
                && !isset($request->data()['query']);
        });
    }

    /** Bug 2 — name mode (default) must use the query parameter. */
    public function test_search_in_name_mode_uses_query_param(): void
    {
        Http::fake([
            '*complexSearch*' => Http::response(['results' => []], 200),
        ]);

        $response = $this->get('/api/spoonacular/search?query=pizza');

        $response->assertOk()->assertJsonPath('success', true);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), '/recipes/complexSearch')
                && $request['query'] === 'pizza'
                && !isset($request->data()['includeIngredients']);
        });
    }

    /** Bug 2 — search without a query returns 400. */
    public function test_search_without_query_returns_400(): void
    {
        Http::fake();
        $this->get('/api/spoonacular/search')->assertStatus(400);
    }

    /** Bug 16 — upstream missing id/title yields 502 instead of leaking partial data. */
    public function test_detail_returns_502_when_upstream_omits_id(): void
    {
        Http::fake([
            '*/information*' => Http::response(['summary' => 'no id here'], 200),
        ]);

        $response = $this->get('/api/spoonacular/detail?recipe_id=12345');

        $response->assertStatus(502)
            ->assertJsonPath('success', false);
    }

    /** Bug 16 — happy-path detail still returns flattened nutrition. */
    public function test_detail_returns_flattened_nutrition_on_success(): void
    {
        Http::fake([
            '*/information*' => Http::response([
                'id' => 99,
                'title' => 'Test Dish',
                'nutrition' => [
                    'nutrients' => [
                        ['name' => 'Calories', 'amount' => 200],
                        ['name' => 'Protein', 'amount' => 10],
                        ['name' => 'Carbohydrates', 'amount' => 30],
                        ['name' => 'Fat', 'amount' => 5],
                    ],
                ],
            ], 200),
        ]);

        $response = $this->get('/api/spoonacular/detail?recipe_id=99');

        $response->assertOk()
            ->assertJsonPath('data.id', 99)
            ->assertJsonPath('data.calories', 200)
            ->assertJsonPath('data.protein', 10)
            ->assertJsonPath('data.carbs', 30)
            ->assertJsonPath('data.fats', 5);
    }

    /** Bug 16 — non-numeric recipe_id is rejected with 400 (no upstream call). */
    public function test_detail_rejects_non_numeric_recipe_id(): void
    {
        Http::fake();
        $this->get('/api/spoonacular/detail?recipe_id=../../etc/passwd')
            ->assertStatus(400);
        Http::assertNothingSent();
    }

    /** Bug 20 — missing SPOONACULAR_BASE_URL falls back to the public endpoint. */
    public function test_base_url_defaults_to_public_endpoint_when_env_missing(): void
    {
        // phpunit.xml does not set SPOONACULAR_BASE_URL, so the env() default
        // declared in config/services.php must take over.
        $this->assertSame(
            'https://api.spoonacular.com',
            config('services.spoonacular.base_url')
        );
    }

    /** Bug 4 — save endpoint requires authentication. */
    public function test_save_requires_auth(): void
    {
        $response = $this->postJson('/api/spoonacular/save', [
            'api_recipe_id' => 1,
            'title' => 'X',
        ]);

        $response->assertStatus(401);
    }

    /** Bug 4 — save validates payload shape. */
    public function test_save_validates_payload(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->postJson('/api/spoonacular/save', [
            'api_recipe_id' => 'not-an-integer',
            'title' => '',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['api_recipe_id', 'title']);
    }
}

