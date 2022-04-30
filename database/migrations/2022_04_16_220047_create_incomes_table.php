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
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount')->comment('Cantidad ingresada');
            $table->string('concepto')->comment('Detalle de este ingreso');
            $table->morphs('incomeable');
            $table->foreignId('user_id')->comment('Usuario que recibe el ingreso')->constrained();
            $table->foreignId('store_id')->comment('Tienda a la que pertenece')->constrained();
            $table->foreignId('place_id')->comment('Sucursal a la que pertenece')->constrained();

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
        Schema::dropIfExists('incomes');
    }
};
