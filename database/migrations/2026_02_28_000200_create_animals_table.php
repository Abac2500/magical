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
        Schema::create('animals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->nullable()
                ->unique()
                ->constrained('users')
                ->nullOnDelete();
            $table->string('name');
            $table->string('nickname')->nullable();
            $table->foreignId('species_id')->constrained('species');
            $table->string('gender', 1);
            $table->date('birth_date');
            $table->string('best_friend_name');
            $table->timestamps();

            $table->index('name');
            $table->index('nickname');
            $table->index('species_id');
            $table->index(['species_id', 'gender', 'id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animals');
    }
};
