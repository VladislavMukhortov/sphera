<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use App\Models\{Post,
    GoalOption,
    GoalRepeat,
    Activity,
    Achievement,
    Follow,
    Profile\UserCareer,
    Profile\UserEducation,
    Profile\UserFamily,
    Profile\UserSkill,
    Report,
    Skill,
    User,
    Goal,
    Task};

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()
            ->count(5)
            ->has(UserCareer::factory()->count(3), 'career')
            ->has(UserEducation::factory()->count(3), 'education')
            ->has(
                Goal::factory()
                    ->count(3)
                    ->afterCreating(function (Goal $goal) {
                        if ($goal->type == 'list') {
                            Task::factory()->count(5)->create(['goal_id' => $goal->id]);
                        } else {
                            GoalOption::factory()->afterCreating(function (GoalOption $goalOption) use ($goal) {
                                GoalRepeat::factory()->count(random_int(0, $goalOption->target_count - 1))
                                    ->create(['goal_id' => $goal->id]);
                            })->create(['goal_id' => $goal->id]);
                        }
                    })
                , 'goals'
            )
            ->has(Post::factory()->count(2), 'posts')
            ->has(UserFamily::factory()->count(1), 'families')
            ->has(Report::factory()->count(2), 'reports')
            ->has(Activity::factory()->count(5), 'activities')
            ->has(Achievement::factory()->count(3), 'achievements')
            ->has(UserSkill::factory()->count(1)->state([
                'title' => 'Test hobby',
            ]), 'skills')
            ->has(UserSkill::factory()->count(1)->state([
                'skill_id' => Skill::all()->random(),
                'mentor' => 1,
            ]), 'skills')
            ->afterCreating(function (User $user) {
                UserSkill::factory()->count(2)->create([
                    'user_id' => $user->id,
                    'parent_id' => $user->mentorSkills()->inRandomOrder()->first('id'),
                    'title' => 'Test mentor skill',
                    'mentor' => 1,
                ]);
                Follow::factory()->count(4)->create(['user_id' => $user->id]);
            })
            ->create();
    }
}
