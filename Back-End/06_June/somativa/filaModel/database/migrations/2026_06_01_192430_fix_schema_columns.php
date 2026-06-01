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
        // Fix clientes: make email NOT NULL since it's required in resource
        Schema::table('clientes', function (Blueprint $table) {
            $table->string('email')->nullable(false)->change();
        });

        // Fix fornecedors: make telefone NOT NULL since it's required in resource
        Schema::table('fornecedors', function (Blueprint $table) {
            $table->string('telefone')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
        });

        Schema::table('fornecedors', function (Blueprint $table) {
            $table->string('telefone')->nullable()->change();
        });
    }
};