<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserFamilyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_family', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\User::class, 'user_id')->constrained()->onDelete('cascade');
            $table->foreignIdFor(\App\Models\User::class, 'relative_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->boolean('is_child')->default(0);
            $table->timestamp('since');
            $table->string('full_name')->nullable();
            $table->string('photo')->nullable();
            $table->string('position')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('user_family');
    }
}
