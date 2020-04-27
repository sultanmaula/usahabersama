<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableNasabahs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('CREATE EXTENSION IF NOT EXISTS "uuid-ossp";');
        Schema::create('nasabahs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama');
            $table->string('alamat');
            $table->uuid('id_kelompok');
            $table->string('no_hp');
            $table->string('nik');
            $table->string('foto');
            $table->date('deleted_at')->nullable();
            $table->timestamps();
        });
        DB::statement('ALTER TABLE nasabahs ALTER COLUMN id SET DEFAULT uuid_generate_v4();');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nasabahs');
    }
}
