<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEssenceLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('essence_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('whoable_type', [
                'App\\\Models\\\Staff',
                'App\\\Models\\\User',
            ]);
            $table->unsignedInteger('whoable_id');
            $table->enum('targetable_type', [
                'App\\\Models\\\Staff',
                'App\\\Models\\\User',
                'App\\\Models\\\Goal',
            ]);
            $table->unsignedInteger('targetable_id');
            $table->string('field_name');
            $table->string('old_value');
            $table->string('new_value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('essence_logs');
    }
}
