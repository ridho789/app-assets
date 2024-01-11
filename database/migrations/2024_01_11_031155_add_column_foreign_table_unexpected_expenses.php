<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnForeignTableUnexpectedExpenses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_unexpected_expenses', function (Blueprint $table) {
            $table->unsignedBigInteger('id_asset')->after('id_unexpected_expenses');
            $table->foreign('id_asset')->references('id_asset')->on('tbl_assets');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_unexpected_expenses', function (Blueprint $table) {
            $table->dropForeign(['id_asset']);
            $table->dropColumn('id_asset');
        });
    }
}
