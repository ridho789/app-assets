<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_assets', function (Blueprint $table) {
            $table->id('id_asset');
            $table->string('name');
            $table->string('location');
            $table->string('purchase_price');
            $table->date('purchase_date');
            $table->text('description');
            $table->string('status');
            $table->string('tot_expenses')->default('0');
            $table->string('tot_overall_expenses')->default('0');
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
        Schema::dropIfExists('tbl_assets');
    }
}
