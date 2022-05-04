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
            $table->string('note')->comment('Nota o descripción opcional')->nullable();
            $table->string('pdfLetter')->comment('PDF en tamaño carta')->nullable();
            $table->string('pdfThermal')->comment('PDF en tamaño térmico')->nullable();
            $table->enum('type', Invoice::TYPES)->default('B00');
            $table->enum('status',['pagado','adeudado','entregado', 'waiting']);
            $table->enum('condition',['De Contado','1 A 15 Días','16 A 30 Días', '31 a 45 Días']);
            $table->enum('payway',['Efectivo','Tarjeta','Transferencia', 'Mixto']);
            $table->foreignId('client_id')->comment('Cliente que hace la compra')->nullable()->constrained();
            $table->foreignId('place_id')->comment('Sucursal a la que pertenece la compra')->constrained();
            $table->foreignId('store_id')->comment('Tienda a la que pertenece la compra')->constrained();
            $table->foreignIdFor(User::class,'seller_id')->references('id')->on('users')->comment('Usuario que genera la venta')->constrained();
            $table->foreignIdFor(User::class, 'contable_id')->references('id')->on('users')->comment('Usuario que cobra la venta')->constrained();
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
