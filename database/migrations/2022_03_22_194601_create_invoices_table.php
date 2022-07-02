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
            $table->string('number');
            $table->enum('isEditable',[true,false]);
            $table->string('note')->comment('Nota o descripción opcional')->nullable();
            $table->string('name')->comment('Para colocar nombre de cliente genérico')->nullable();
            $table->string('rnc')->comment('Para colocar rnc de cliente genérico')->nullable();
            $table->enum('type', Invoice::TYPES)->default('B00');
            $table->enum('status',['waiting','anulada','cerrada']);
            $table->enum('condition',['De Contado','1 A 15 Días','16 A 30 Días', '31 a 45 Días']);
            $table->enum('payway',['Efectivo','Tarjeta','Transferencia', 'Mixto']);
            $table->decimal('rest', 14,4)->comment('Deuda pendiente de la factura');
            $table->decimal('gasto', 14,4)->comment('Costo de compra de los productos');
            $table->decimal('gasto_service', 14,4)->comment('Costo de generación de los servicios');
            $table->decimal('venta', 14,4)->comment('Ingreso de venta de los productos');
            $table->decimal('venta_service', 14,4)->comment('Ingreso de venta de los servicios');
            $table->decimal('ingreso', 14,4)->comment('Costo de venta de los productos');
            $table->foreignId('client_id')->comment('Cliente que hace la compra')->nullable()->constrained();
            $table->foreignId('place_id')->comment('Sucursal a la que pertenece la compra')->constrained();
            $table->foreignId('store_id')->comment('Tienda a la que pertenece la compra')->constrained()->on('moso_master.stores');
            $table->foreignIdFor(User::class,'seller_id')->references('id')->on('users')->comment('Usuario que genera la venta')->constrained()->on('moso_master.users');
            $table->foreignIdFor(User::class, 'contable_id')->references('id')->on('users')->comment('Usuario que cobra la venta')->constrained()->on('moso_master.users');
            $table->date('expires_at');
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
