<?php

use App\Models\{Goal, GoalOption};
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoalsOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goals_options', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Goal::class, 'goal_id')->constrained()->onDelete('cascade');
            $table->string('action_button');
            $table->integer('target_count');
            $table->enum('unit', GoalOption::REPEAT_TYPES);
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
        Schema::dropIfExists('goals_options');
    }
}
