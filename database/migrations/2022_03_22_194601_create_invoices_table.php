<?php

use App\Models\Invoice;
use App\Models\User;
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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->uuid('uid');
            $table->date('day');
            $table->decimal('amount')->comment('Cantidad real de la venta');
            $table->decimal('discount')->comment('Descuento general de la factura');
            $table->decimal('total')->comment('Monto total de la factura menos descuentos y más impuestos');
            $table->decimal('payed')->comment('Monto pagado de la venta');
            $table->decimal('rest')->comment('Monto adeudado de la venta');
            $table->decimal('efectivo')->comment('Monto pagado en efectivo')->default();
            $table->decimal('tarjeta')->comment('Monto pagado por tarjeta o cheque')->default();
            $table->decimal('transferencia')->comment('Monto pagado por transferencia')->default();
            $table->foreignIdFor(User::class,'seller_id')->references('id')->on('users')->comment('Usuario que genera la venta')->constrained();
            $table->foreignIdFor(User::class, 'contable_id')->references('id')->on('users')->comment('Usuario que cobra la venta')->constrained();
            $table->foreignId('client_id')->comment('Cliente que hace la compra')->constrained();
            $table->foreignId('place_id')->comment('Sucursal a la que pertenece la compra')->constrained();
            $table->foreignId('store_id')->comment('Tienda a la que pertenece la compra')->constrained();
            $table->enum('type',array_keys(Invoice::TYPES));
            $table->enum('status',['pendiente/pagado','pendiente/adeudado','entregado']);
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
        Schema::dropIfExists('invoices');
    }
};
