<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('pokemon_locals')) {
            return;
        }

        Schema::create('pokemon_locals', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('caminho_imagem'); // Aqui salvamos o caminho da imagem
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pokemon_locals');
    }
};
