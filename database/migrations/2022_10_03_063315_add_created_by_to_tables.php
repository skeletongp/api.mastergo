<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        $tables=DB::select('SHOW TABLES');
        foreach ($tables as $table) {
            $table=collect($table)->first();
            if (!Schema::hasColumn($table, 'created_by') && $table!='migrations') {
                Schema::table($table, function (Blueprint $table) {
                    $table->unsignedBigInteger('created_by')->nullable()->default(1);
                    $table->foreign('created_by')->references('id')->on('users');
                });
            }
            if (!Schema::hasColumn($table, 'updated_by') && $table!='migrations') {
                Schema::table($table, function (Blueprint $table) {
                    $table->unsignedBigInteger('updated_by')->nullable()->default(1);
                    $table->foreign('updated_by')->references('id')->on('users');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tables=DB::select('SHOW TABLES');
        foreach ($tables as $table) {
            $table=collect($table)->first();
            if (Schema::hasColumn($table, 'created_by')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->dropForeign(['created_by']);
                 
                });
            }
            if (Schema::hasColumn($table, 'updated_by')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->dropForeign(['updated_by']);
                });
            }
        }
    }
};
