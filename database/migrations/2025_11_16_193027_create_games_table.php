<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->json('board')->nullable(); // ["", "", "", "", "", "", "", "", ""]
            $table->string('turn')->dafault('X');
            $table->unsignedBigInteger('player_x')->nullable();
            $table->unsignedBigInteger('player_0')->nullable();
            $table->string('winner')->nullable();
            $table->boolean('is_draw')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
