<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrackedOlxSearchesTable extends Migration
{
    public function up()
    {
        Schema::create('tracked_olx_searches', function (Blueprint $table) {
            $table->id();
            $table->string('url', 500)->unique();
            $table->integer('tracked_pages_amount')->nullable();
            $table->boolean('active')->default(false);
            $table->unsignedSmallInteger('tracking_interval')->default(60);
            $table->timestamp('last_tracked_at')->nullable();
            $table->timestamps();
            $table->softDeletes('deleted_at', 0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('tracked_olx_searches');
    }
}
