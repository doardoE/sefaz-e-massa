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
        Schema::create('arrecadacoes', function (Blueprint $table) {
            $table->id();
            $table->string('tributo');
            $table->integer('mes');
            $table->integer('ano');
            $table->decimal('valor', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arrecadacoes');
    }
};
