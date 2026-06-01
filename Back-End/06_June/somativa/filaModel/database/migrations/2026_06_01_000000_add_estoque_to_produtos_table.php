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
        Schema::table('produtos', function (Blueprint $table) {
            // Remove previous incorrect column if exists and add correct stock/value fields
            if (Schema::hasColumn('produtos', 'valor_total')) {
                $table->dropColumn('valor_total');
            }

            $table->decimal('valor_unitario', 10, 2)->default(0)->after('descricao');
            $table->integer('quantidade')->default(0)->after('valor_unitario');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produtos', function (Blueprint $table) {
            if (Schema::hasColumn('produtos', 'quantidade')) {
                $table->dropColumn('quantidade');
            }
            if (Schema::hasColumn('produtos', 'valor_unitario')) {
                $table->dropColumn('valor_unitario');
            }
            // restore legacy column
            if (! Schema::hasColumn('produtos', 'valor_total')) {
                $table->string('valor_total')->nullable();
            }
        });
    }
};
