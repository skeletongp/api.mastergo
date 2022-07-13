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
        Schema::table('creditnotes', function (Blueprint $table) {
            $table->foreignId('comprobante_id')->nullable()->after('place_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('creditnotes', function (Blueprint $table) {
            $table->dropForeign(['comprobante_id']);
        });
    }
};
