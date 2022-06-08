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
        Schema::create('product_productions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_id')->comment('Producción vinculada')->constrained();
            $table->morphs('productible');
            $table->morphs('unitable');
            $table->decimal('cant')->comment('Cantidad del producto que se obtuvo');
            $table->decimal('stock')->comment('Campo puesto mientras tanto');
            $table->enum('status',['open','close']);
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
        Schema::dropIfExists('product_productions');
    }
};
