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
        Schema::table('clients', function (Blueprint $table) {
            $table->decimal('debt', 12, 4)->default(0)->after('limit');
        });
        Schema::table('preferences', function (Blueprint $table) {
            $table->enum('instant',['yes','no'])->default('no')->after('printer_ver')->comment('Si es yes, se cobra la factura al momento de crearla');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('table', function (Blueprint $table) {
            //
        });
    }
};
