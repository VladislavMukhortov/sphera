<?php

namespace Database\Seeders;

use App\Models\Follow;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FollowSeeder extends Seeder
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
        for ($i = 0; $i <= 200; $i++) {
            Follow::factory()
                ->count(1000)
                ->create();
        }
    }
}
