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
        Schema::create('paras', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('banner',300)->nullable();
            $table->text('paragraph')->nullable(false);
            $table->string('alt',300)->nullable();
            $table->integer('article_id')->nullable(false);
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
