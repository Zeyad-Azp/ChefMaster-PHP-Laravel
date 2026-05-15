<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('my_recipes', function (Blueprint $table) {
            $table->id();
<<<<<<< HEAD
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
=======
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
>>>>>>> e1b21b8101c145ef6af786483709267652d41b6a

            $table->string('title');
            $table->text('description')->nullable();
            $table->longText('ingredients');
            $table->longText('instructions');
            $table->decimal('calories', 8, 2)->default(0);
            $table->decimal('protein', 8, 2)->default(0);
            $table->decimal('carbs', 8, 2)->default(0);
            $table->decimal('fats', 8, 2)->default(0);
            $table->string('image_path', 500)->nullable();
            $table->enum('source_type', ['manual', 'api'])->default('manual');
            $table->string('api_recipe_id')->nullable();
            $table->boolean('is_favorite')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('my_recipes');
    }
};
