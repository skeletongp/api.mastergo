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
        Schema::create('pesadas', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount')->comment('Cantidad de la pesada');
            $table->string('unit')->comment('Unidad de la pesada');
            $table->decimal('unit_value')->comment('Valor por unidad de la pesada');
            $table->decimal('total_value')->comment('Valor total de la pesada');
            $table->enum('type',['producto','materia prima'])->comment('Tipo de pesada');
            $table->morphs('pesable');
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable()->default(1);
            $table->foreign('created_by')->references('id')->on('moso_master.users');
            $table->unsignedBigInteger('updated_by')->nullable()->default(1);
            $table->foreign('updated_by')->references('id')->on('moso_master.users');
            $table->foreignId('store_id')->constrained()->on('moso_master.stores');
            $table->foreignId('place_id')->constrained();
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
        Schema::dropIfExists('pesadas');
    }
};
