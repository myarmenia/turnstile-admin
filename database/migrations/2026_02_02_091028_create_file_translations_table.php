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
        Schema::create('file_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('lang', 5); // hy, ru, en
            $table->string('title')->nullable();
            $table->string('alt')->nullable();
            $table->timestamps();

            $table->unique(['file_id', 'lang']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_translations');
    }
};
