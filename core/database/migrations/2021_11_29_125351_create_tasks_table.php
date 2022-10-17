<?php

use App\Models\Goal;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Goal::class, 'goal_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('comment')->nullable();
            $table->integer('price')->nullable();
            $table->string('schedule')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->timestamp('start_at')->nullable();
            $table->timestamp('deadline_at')->nullable();
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
        Schema::dropIfExists('tasks');
    }
}
