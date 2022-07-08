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
            $table->decimal('income',14,4);
            $table->decimal('currency_rate',14,4);
            $table->foreignId('debitable_id')->references('id')->on('counts')->constrained();
            $table->decimal('outcome',14,4);
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
