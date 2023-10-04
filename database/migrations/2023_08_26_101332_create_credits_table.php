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
        Schema::create('credits', function (Blueprint $table) {
            $table->id();
            $table->string('comment');
            $table->string('modified_ncf');
            $table->string('ncf');
            $table->decimal('amount');
            $table->decimal('itbis');
            $table->decimal('selectivo');
            $table->decimal('propina');
            $table->date('modified_at');
            $table->foreignId('user_id')->constrained()->on('moso_master.users');
            $table->morphs('creditable');
            $table->foreignId('place_id')->constrained();
            $table->foreignId('comprobante_id')->nullable()->constrained();
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
        Schema::dropIfExists('credits');
    }
};
