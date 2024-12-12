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
        Schema::create('images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('url'); // URL o ruta de la imagen
            $table->string('type'); // 'thumbnail' o 'gallery'
            $table->unsignedBigInteger('imageable_id'); // ID del modelo asociado
            $table->string('imageable_type'); // Clase del modelo asociado
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
