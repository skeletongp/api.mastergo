<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuadres', function (Blueprint $table) {
            $table->id();
            $table->decimal('efectivo', 14,4);
            $table->decimal('tarjeta', 14,4);
            $table->decimal('transferencia', 14,4);
            $table->decimal('contado', 14,4);
            $table->decimal('credito', 14,4);
            $table->decimal('cobro', 14,4);
            $table->decimal('devolucion', 14,4);
            $table->decimal('egreso', 14,4);
            $table->decimal('total', 14,4);
            $table->date('day');
            $table->foreignId('place_id')->constrained();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cuadres');
    }
};
