<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableAddSisaLabaColumn extends Migration
{

    public function up()
    {
        Schema::table('angsurans', function (Blueprint $table) {
               $table->integer('sisa_laba')->nullable();
        });
    }

    public function down()
    {
        Schema::table('angsurans', function (Blueprint $table) {
            //
        });
    }
}
