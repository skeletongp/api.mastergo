<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'moso_master';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::connection($this->connection)->whenTableDoesntHaveColumn('users','place_id', function (Blueprint $table) {
          
                $table->foreignId('place_id')->constrained()->on(env('DB_DATABASE') . '.places');
                $table->foreignId('store_id')->constrained()->on('moso_master.stores');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('moso_master')->table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('place_id');
            $table->dropConstrainedForeignId('store_id');
        });
    }
};
