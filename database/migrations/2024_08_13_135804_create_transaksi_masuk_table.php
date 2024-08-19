<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksiMasukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi_masuk', function (Blueprint $table) {
            $table->id();
            $table->string('no_trans')->unique();
            $table->string('keterangan')->nullable();
            $table->unsignedBigInteger('pasien_id');
            $table->date('tgl');
            $table->integer('total');
            $table->string('harga_periksa');
            $table->timestamps();
            $table->foreign('pasien_id')->references('id')->on('pasien')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksi_masuk');
    }
}
