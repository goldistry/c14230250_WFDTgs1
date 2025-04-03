<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('drama_genres', function (Blueprint $table) {
            $table->uuid('drama_id');
            $table->uuid('genre_id');

            // Foreign keys
            $table->foreign('drama_id')->references('id')->on('promotions')->onDelete('cascade');
            $table->foreign('genre_id')->references('id')->on('genres')->onDelete('cascade');

            // Unique pair
            $table->primary(['drama_id', 'genre_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drama_genres');
    }
};
