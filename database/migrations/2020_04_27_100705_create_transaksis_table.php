<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('CREATE EXTENSION IF NOT EXISTS "uuid-ossp";');
        Schema::create('transaksis', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_produk')->nullable();
            $table->integer('harga_produk')->nullable();
            $table->uuid('id_nasabah')->nullable();
            $table->integer('total_pinjaman')->nullable();
            $table->date('tanggal')->nullable();
            $table->integer('jumlah_cicilan')->nullable();
            $table->integer('sisa_pinjaman')->nullable();
            $table->integer('status')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        DB::statement('ALTER TABLE transaksis ALTER COLUMN id SET DEFAULT uuid_generate_v4();');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksis');
    }
}
