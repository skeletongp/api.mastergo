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
        Schema::create('product_place_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained();
            $table->foreignId('unit_id')->constrained();
            $table->foreignId('place_id')->constrained();
            $table->decimal('price_mayor',11,4)->comment('Precio del producto al por mayor');
            $table->decimal('price_menor',11,4)->comment('Precio del producto al detalle');
            $table->decimal('min',11,4)->comment('Cantidad mínima para considerar un producto al por mayor');
            $table->decimal('cost',11,4)->comment('Costo base del producto (valor de compra)');
            $table->decimal('margin',11,4)->comment('Ganancia porcentual que genera el producto');
            $table->decimal('stock',11,4)->comment('Cantidad de disponible de esta unidad)');
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
        Schema::dropIfExists('product_place_units');
    }
};
