<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goals', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignIdFor(\App\Models\User::class, 'user_id')->constrained()->onDelete('cascade');
            $table->foreignIdFor(\App\Models\User::class, 'mentor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignIdFor(\App\Models\Skill::class, 'skill_id')->nullable();
            $table->enum('type', \App\Models\Goal::TYPES);
            $table->enum('status', \App\Models\Goal::STATUSES);
            $table->timestamp('start_at')->nullable();
            $table->timestamp('deadline_at')->nullable();
            $table->timestamp('paused_at')->nullable();
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
        Schema::dropIfExists('goals');
    }
}
