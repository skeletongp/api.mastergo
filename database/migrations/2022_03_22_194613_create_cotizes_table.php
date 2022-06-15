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
        Schema::create('cotizes', function (Blueprint $table) {
            $table->id();
            $table->uuid('uid');
            $table->date('day');
            $table->decimal('amount', 14,4)->comment('Cantidad real de la venta');
            $table->decimal('discount', 14,4)->comment('Descuento general de la cotización');
            $table->decimal('total', 14,4)->comment('Monto total de la cotización menos descuentos y más impuestos');
            $table->foreignId('user_id')->comment('Usuario que genera la venta')->constrained()->on('moso_master.users');
            $table->foreignId('client_id')->comment('Cliente que hace la compra')->constrained();
            $table->foreignId('place_id')->comment('Sucursal a la que pertenece la compra')->constrained();
            $table->foreignId('store_id')->comment('Tienda a la que pertenece la compra')->constrained()->on('moso_master.stores');
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
        Schema::dropIfExists('cotizes');
    }
};
