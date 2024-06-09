<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksiKeluarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi_keluar', function (Blueprint $table) {
            $table->id();
            $table->string('no_trans')->unique();
            $table->string('keterangan')->nullable();
            $table->unsignedBigInteger('pemasok_id');
            $table->date('tgl');
            $table->integer('total');
            $table->timestamps();
            $table->foreign('pemasok_id')->references('id')->on('pemasok')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksi_keluar');
    }
}
