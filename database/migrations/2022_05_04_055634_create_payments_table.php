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
            $table->date('day');
           // $table->string('number');
            $table->decimal('amount', 14,4)->comment('Cantidad real a pagar');
            $table->decimal('discount', 14,4)->comment('Descuento general del pago si aplica');
            $table->decimal('total', 14,4)->comment('Monto total del pago menos descuentos y más impuestos');
            $table->decimal('tax', 14,4)->comment('Total de impuestos del pago');
            $table->decimal('payed', 14,4)->comment('Monto pagado del pago');
            $table->decimal('rest', 14,4)->comment('Monto adeudado del pago');
            $table->decimal('cambio', 14,4)->comment('Lo que se le devuelve al cliente');
            $table->decimal('efectivo', 14,4)->comment('Monto pagado en efectivo')->default();
            $table->decimal('tarjeta', 14,4)->comment('Monto pagado por tarjeta o cheque')->default();
            $table->decimal('transferencia', 14,4)->comment('Monto pagado por transferencia')->default();
            $table->morphs('payable');
            $table->morphs('contable');
            $table->morphs('payer');
            $table->foreignId('place_id')->constrained();
            $table->enum('forma',['cobro','contado','credito']);
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
