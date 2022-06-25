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
        Schema::create('provisions', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->decimal('cant',14,4);
            $table->decimal('cost',14,4);
            $table->foreignId('provider_id');
            $table->morphs('atribuible');
            $table->morphs('provisionable');
            $table->foreignId('place_id')->constrained();
            $table->foreignId('user_id')->constrained()->on('moso_master.users');
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
        Schema::dropIfExists('provisions');
    }
};
