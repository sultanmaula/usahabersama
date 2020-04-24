<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableMarginKeuntungans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('CREATE EXTENSION IF NOT EXISTS "uuid-ossp";');
        Schema::create('margin_keuntungans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('prosentase');
            $table->date('deleted_at')->nullable();
            $table->timestamps();
        });
        DB::statement('ALTER TABLE margin_keuntungans ALTER COLUMN id SET DEFAULT uuid_generate_v4();');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('margin_keuntungans');
    }
}
