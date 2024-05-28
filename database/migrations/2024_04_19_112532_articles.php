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
        Schema::create('articles', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('title',300)->nullable(false);
            $table->string('meta',300)->nullable(false);
            $table->string('banner',300)->nullable(false);
            $table->text('introduction')->nullable(false);
            $table->string('alt',300)->nullable(false);
            $table->integer('category_id')->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
