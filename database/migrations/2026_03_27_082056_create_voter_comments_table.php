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
        Schema::create('voter_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voter_id')->constrained()->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->longText('comment');
            $table->string('file_path')->nullable();
            $table->enum('file_type', ['image','audio','video','document'])->nullable();
            $table->string('category')->nullable();
            $table->boolean('is_important')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voter_comments');
    }
};
