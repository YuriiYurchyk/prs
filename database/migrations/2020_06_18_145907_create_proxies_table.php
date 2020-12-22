<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProxiesTable extends Migration
{
    public function up()
    {
        Schema::create('proxies', function (Blueprint $table) {
            $table->id();
            $table->string('ip_port', 21);
            $table->string('password', 50)->nullable();
            $table->string('login', 50)->nullable();
            $table->boolean('alive')->default(1);
            $table->timestamps();
            $table->softDeletes('deleted_at', 0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('proxies');
    }
}
