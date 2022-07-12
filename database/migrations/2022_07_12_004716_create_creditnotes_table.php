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
        Schema::create('creditnotes', function (Blueprint $table) {
            $table->id();
            $table->string('comment');
            $table->date('modified_at');
            $table->string('modified_ncf');
            $table->foreignId('user_id')->constrained()->on('moso_master.users');
            $table->foreignId('invoice_id')->constrained();
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
        Schema::dropIfExists('creditnotes');
    }
};
