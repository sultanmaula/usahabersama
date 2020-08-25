<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTransaksisTableAddColumnDp extends Migration
{
    public function up()
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->integer('dp')->nullable();
        });
    }

    public function down()
    {
        Schema::table('transaksis', function (Blueprint $table) {
            //
        });
    }
}