<?php

use App\Models\{Goal, Offer, User};
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'user_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'sender_id')->constrained('users')->cascadeOnDelete();
            $table->foreignIdFor(Goal::class, 'goal_id')->nullable()->constrained();
            $table->enum('type', Offer::TYPES);
            $table->integer('amount')->nullable();
            $table->enum('status', Offer::STATUSES)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
}
