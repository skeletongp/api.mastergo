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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('ncf')->comment('Comprobante fiscal que corresponde al pago')->nullable();
            $table->decimal('amount')->comment('Cantidad real a pagar');
            $table->decimal('discount')->comment('Descuento general del pago si aplica');
            $table->decimal('total')->comment('Monto total del pago menos descuentos y más impuestos');
            $table->decimal('tax')->comment('Total de impuestos del pago');
            $table->decimal('payed')->comment('Monto pagado del pago');
            $table->decimal('rest')->comment('Monto adeudado del pago');
            $table->decimal('cambio')->comment('Lo que se le devuelve al cliente');
            $table->decimal('efectivo')->comment('Monto pagado en efectivo')->default();
            $table->decimal('tarjeta')->comment('Monto pagado por tarjeta o cheque')->default();
            $table->decimal('transferencia')->comment('Monto pagado por transferencia')->default();
            $table->morphs('payable');
            $table->morphs('payer');
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
        Schema::dropIfExists('payments');
    }
};
