<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255)->index();
            $table->foreignId('author_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->date('release_date')->index();
            $table->decimal('price_huf', 10, 2)->index();
            $table->timestamps();

            $table->index(['category_id', 'price_huf']);
            $table->index(['author_id', 'title']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
