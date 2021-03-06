<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEditTbsPenjualansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edit_tbs_penjualans', function (Blueprint $table) {
            $table->increments('id_edit_tbs_penjualans');
            $table->string('session_id');
            $table->string('no_faktur');
            $table->integer('id_barang');
            $table->integer('jumlah_barang');
            $table->string('total_harga');
            $table->string('status_barang')->nullable();
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
        Schema::dropIfExists('edit_tbs_penjualans');
    }
}
