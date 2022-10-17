<?php

use App\Models\{Profile\UserSkill, Skill, User};
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSkillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'user_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Skill::class, 'skill_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(UserSkill::class, 'parent_id')->nullable()->constrained('user_skills')->cascadeOnDelete();
            $table->string('title', 255)->nullable();
            $table->boolean('mentor')->default(0);
            $table->integer('amount')->nullable();
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
        Schema::dropIfExists('user_skills');
    }
}
