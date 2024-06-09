<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailKeluarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_keluar', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaksi_keluar_id');
            $table->unsignedBigInteger('obat_id')->nullable();
            $table->unsignedBigInteger('barang_id')->nullable(); 
            $table->timestamps();

            $table->foreign('transaksi_keluar_id')->references('id')->on('transaksi_keluar')->onDelete('cascade');
            $table->foreign('obat_id')->references('id')->on('obat')->onDelete('cascade');
            $table->foreign('barang_id')->references('id')->on('barang')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_keluar');
    }
}
