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
        Schema::create('itens_pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->contrained('pedidos')->cascadeOneDelete();
            $table->foreignId('produto_id')->contrained('produtos')->cascadeOneDelete();
            $table->string('quantidade');
            $table->string('preco_un', 10,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itens__pedidos');
    }
};
