<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOlxSearchOlxAdTable extends Migration
{
    public function up()
    {
        Schema::create('olx_search_olx_ad', function (Blueprint $table) {
            $table->bigInteger('olx_ad_id');
            $table->bigInteger('olx_search_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('olx_search_olx_ad');
    }
}
