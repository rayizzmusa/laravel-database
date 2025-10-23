<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // up ini untuk menjalankan
    public function up(): void
    {
        Schema::create('counter', function (Blueprint $table) {
            $table->string("id", 100)->nullable(false)->primary();
            $table->integer("counter")->nullable(false)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    // up ini untuk menjalankan
    public function down(): void
    {
        Schema::dropIfExists('counter');
    }
};
