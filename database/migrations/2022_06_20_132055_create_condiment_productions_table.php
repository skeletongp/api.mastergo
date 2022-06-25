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
        Schema::create('condiment_productions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_id')->comment('Producción vinculada')->constrained();
            $table->foreignId('condiment_id')->comment('Condimento utilizado en el proceso')->constrained();
            $table->string('attribute')->comment('Atributo del condimento')->constrained();
            $table->foreignId('unit_id')->constrained();
            $table->decimal('cant')->comment('Cantidad del condimento que se utiliza');
            $table->decimal('cost')->comment('Costo del condimento que se utiliza');
            $table->decimal('total')->comment('cant / cost del condimento que se utiliza');
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
        Schema::dropIfExists('condiment_productions');
    }
};
