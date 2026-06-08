<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->unique('cpf', 'clientes_cpf_unique');
            $table->unique('email', 'clientes_email_unique');
        });

        Schema::table('fornecedors', function (Blueprint $table) {
            $table->unique('cnpj', 'fornecedors_cnpj_unique');
            $table->unique('email', 'fornecedors_email_unique');
        });

        Schema::table('produtos', function (Blueprint $table) {
            $table->unique('nome', 'produtos_nome_unique');
        });

        Schema::table('insumos', function (Blueprint $table) {
            $table->unique('nome', 'insumos_nome_unique');
        });
    }

    public function down(): void
    {
        Schema::table('insumos', function (Blueprint $table) {
            $table->dropUnique('insumos_nome_unique');
        });

        Schema::table('produtos', function (Blueprint $table) {
            $table->dropUnique('produtos_nome_unique');
        });

        Schema::table('fornecedors', function (Blueprint $table) {
            $table->dropUnique('fornecedors_cnpj_unique');
            $table->dropUnique('fornecedors_email_unique');
        });

        Schema::table('clientes', function (Blueprint $table) {
            $table->dropUnique('clientes_cpf_unique');
            $table->dropUnique('clientes_email_unique');
        });
    }
};
