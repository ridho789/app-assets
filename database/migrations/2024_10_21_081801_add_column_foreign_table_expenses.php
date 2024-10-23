<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnForeignTableExpenses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_expenses', function (Blueprint $table) {
            $table->unsignedBigInteger('id_asset')->after('id_expense');
            $table->foreign('id_asset')->references('id_asset')->on('tbl_assets');

            $table->unsignedBigInteger('id_category')->after('id_asset');
            $table->foreign('id_category')->references('id_category')->on('tbl_category');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_expenses', function (Blueprint $table) {
            $table->dropForeign(['id_asset']);
            $table->dropColumn('id_asset');

            $table->dropForeign(['id_category']);
            $table->dropColumn('id_category');
        });
    }
}
