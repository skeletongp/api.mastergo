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
        Schema::create('details', function (Blueprint $table) {
            $table->id();
            $table->decimal('cant',14,4)->comment('Cantidad del producto que se factura');
            $table->decimal('price',14,4)->comment('Precio en que se vende/cotiza el producto');
            $table->decimal('subtotal',14,4)->comment('Subtotal de lo que hace el detalle');
            $table->decimal('total',14,4)->comment('Total de lo que hace el detalle');
            $table->decimal('cost',14,4)->comment('Costo que tenía el producto al momento de ser añadido');
            $table->decimal('taxtotal',14,4)->comment('Total de impuestos del detalle');
            $table->decimal('discount',14,4)->comment('Descontado al producto en dinero');
            $table->decimal('discount_rate',14,4)->comment('Descontado al producto en porciento');
            $table->enum('price_type',['mayor','detalle'])->comment('Tipo de precio que se cobró (Al por mayor o detalle)');
            $table->decimal('utility',14,4)->comment('Ganancia que deja este detalle (cant*price)-(cant*cost)');
            $table->foreignId('product_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('store_id')->constrained();
            $table->foreignId('place_id')->constrained();
            $table->morphs('detailable');
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
        Schema::dropIfExists('details');
    }
};
