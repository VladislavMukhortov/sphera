<?php

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ini_set('memory_limit', '8000M');//allocate memory
        DB::disableQueryLog();//disable log
        for ($i = 0; $i <= 100; $i++) {
            Achievement::factory()
                ->count(1000)
                ->create();
        }
    }
}
