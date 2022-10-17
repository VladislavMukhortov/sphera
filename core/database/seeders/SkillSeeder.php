<?php

namespace Database\Seeders;

use App\Models\{Skill, SkillLocale};
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Skill::factory()
            ->has(SkillLocale::factory()->state(new Sequence(
                ['title' => 'Психология', 'lang' => 'ru'],
                ['title' => 'Флористика', 'lang' => 'ru'],
                ['title' => 'Физика', 'lang' => 'ru'],
                ['title' => 'Маркетинг', 'lang' => 'ru'],
                ['title' => 'Садоводство', 'lang' => 'ru'],
                ['title' => 'Музыка', 'lang' => 'ru'],
                ['title' => 'Образование', 'lang' => 'ru'],
                ['title' => 'Путешествия', 'lang' => 'ru'],
                ['title' => 'Медицина', 'lang' => 'ru'],
                ['title' => 'Дайвинг', 'lang' => 'ru'],
            )), 'locales')
            ->has(SkillLocale::factory()->state(new Sequence(
                ['title' => 'Psychology', 'lang' => 'en'],
                ['title' => 'Florist', 'lang' => 'en'],
                ['title' => 'Physics', 'lang' => 'en'],
                ['title' => 'Marketing', 'lang' => 'en'],
                ['title' => 'Gardening', 'lang' => 'en'],
                ['title' => 'Music', 'lang' => 'en'],
                ['title' => 'Education', 'lang' => 'en'],
                ['title' => 'Travel', 'lang' => 'en'],
                ['title' => 'Medicine', 'lang' => 'en'],
                ['title' => 'Diving', 'lang' => 'en'],
            )), 'locales')
            ->count(10)->create();
    }
}
