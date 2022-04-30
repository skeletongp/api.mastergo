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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('concepto');
            $table->string('ref');
            $table->date('day');
            $table->decimal('income',11,5);
            $table->foreignId('debitable_id')->references('id')->on('counts')->constrained();
            $table->decimal('outcome',11,4);
            $table->foreignId('creditable_id')->references('id')->on('counts')->constrained();
            $table->foreignId('place_id')->constrained();
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
        Schema::dropIfExists('transactions');
    }
};
