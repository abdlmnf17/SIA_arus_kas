<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasienTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pasien', function (Blueprint $table) {
            $table->id();
            $table->string('nm_pasien');
            $table->string('umur');
            $table->string('alamat');
            $table->string('tensi');
            $table->string("no_antrian")->nullable();
            $table->string("keluhan")->nullable();
            $table->string("diagnosa")->nullable();
            $table->string("keterangan_dosis")->nullable();
            $table->integer("total")->nullable();
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
        Schema::dropIfExists('pasien');
    }
}
