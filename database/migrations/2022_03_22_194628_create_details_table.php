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
            $table->decimal('cant',11,4)->comment('Cantidad del producto que se factura');
            $table->decimal('price',11,4)->comment('Precio en que se vende/cotiza el producto');;
            $table->decimal('cost',11,4)->comment('Costo que tenía el producto al momento de ser añadido');
            $table->decimal('utility',11,4)->comment('Ganancia que deja este detalle (cant*price)-(cant*cost)');
            $table->foreignId('product_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('client_id')->constrained();
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
