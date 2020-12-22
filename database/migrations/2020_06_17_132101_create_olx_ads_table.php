<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOlxAdsTable extends Migration
{
    public function up()
    {
        Schema::create('olx_ads', function (Blueprint $table) {
            $table->id();
            $table->string('url', 500)->unique();
            $table->string('name', 100);
            $table->integer('price');
            $table->string('currency', 10);
            $table->text('description');
            $table->boolean('favorite')->default(0);
            $table->dateTime('publication_at');
            $table->dateTime('last_active_at');
            $table->dateTime('not_found_at')->nullable();
            $table->timestamps();
            $table->softDeletes('deleted_at', 0);

            $table->engine    = 'InnoDB';
            $table->charset   = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });
    }

    public function down()
    {
        Schema::dropIfExists('olx_ads');
    }
}
