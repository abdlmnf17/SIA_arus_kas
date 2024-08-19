<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailTranskasiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_transaksi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaksi_masuk_id')->nullable(); // Foreign Key: transaksi_masuk_id
            $table->unsignedBigInteger('transaksi_keluar_id')->nullable(); // Foreign Key: transaksi_keluar_id
            $table->integer('total'); // Total
            $table->timestamps();

            // Menambahkan constraint foreign key
            $table->foreign('transaksi_masuk_id')->references('id')->on('transaksi_masuk');
            $table->foreign('transaksi_keluar_id')->references('id')->on('transaksi_keluar');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_transaksi');
    }
}
