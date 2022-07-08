<?php

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
        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->string('bank_name')->comment('Banco en que está la cuenta');
            $table->string('bank_number')->comment('Número de la cuenta');
            $table->string('titular')->nullable()->comment('Titular de la cuenta');
            $table->enum('currency',['DOP','USD'])->default('DOP')->comment('Moneda en que se encuentra la cuenta');
            $table->foreignId('store_id')->constrained()->on('moso_master.stores');
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
        Schema::dropIfExists('banks');
    }
};
