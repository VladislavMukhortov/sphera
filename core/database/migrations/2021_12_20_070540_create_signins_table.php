<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSigninsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('signins', function (Blueprint $table) {
            $table->enum('who', ['staff', 'user']);
            $table->unsignedInteger('who_id');
            $table->string('ip', 15)->nullable();
            $table->boolean('is_mobile')->default(1);
            $table->string('location', 32)->nullable();
            $table->string('region', 32)->nullable();
            $table->string('device', 32)->nullable();
            $table->string('user_agent')->nullable();
            $table->string('os', 32)->nullable();
            $table->string('os_ver', 32)->nullable();
            $table->string('browser', 32)->nullable();
            $table->string('browser_ver', 32)->nullable();
            $table->dateTime('created_at');
            $table->index(['who_id', 'who', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('signins');
    }
}
