<?php

use App\Models\{City, Country};
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('email', 64)->unique()->nullable();
            $table->string('phone', 22)->unique()->nullable();
            $table->enum('gender', ['male', 'female', 'other']);
            $table->char('lang', 2)->default(config('app.locale'));
            $table->boolean('is_banned')->default(0);
            $table->boolean('is_mentor')->default(0);
            $table->string('first_name', 60)->nullable();
            $table->string('last_name', 60)->nullable();
            $table->date('birthday')->nullable();
            $table->foreignIdFor(Country::class, 'country_id')->nullable();
            $table->foreignIdFor(City::class, 'city_id')->nullable();
            $table->string('google_id')->nullable();
            $table->string('facebook_id')->nullable();
            $table->string('photo')->nullable();
            $table->float('rating')->nullable();
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
        Schema::dropIfExists('users');
    }
}
