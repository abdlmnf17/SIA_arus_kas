<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJurnalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jurnal', function (Blueprint $table) {
            $table->id();
            $table->string('keterangan'); // Keterangan
            $table->unsignedBigInteger('detail_transaksi_id'); // Foreign Key: detail_transaksi_id
          
            $table->string('debit'); // Debit
            $table->string('kredit'); // Kredit
            $table->integer('total'); // Total
            $table->timestamps();

            // Menambahkan constraint foreign key
            $table->foreign('detail_transaksi_id')->references('id')->on('detail_transaksi');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jurnal');
    }
}
