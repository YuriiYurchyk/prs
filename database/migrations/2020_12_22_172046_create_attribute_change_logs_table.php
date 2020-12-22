<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttributeChangeLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attribute_change_logs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('attribute');
            $table->string('value');
            $table->bigInteger('attribute_change_logable_id');
            $table->string('attribute_change_logable_type');

        });
    }

    public function down()
    {
        Schema::dropIfExists('attribute_change_logs');
    }
}
