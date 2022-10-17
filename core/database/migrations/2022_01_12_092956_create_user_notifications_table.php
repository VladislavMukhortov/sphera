<?php

use App\Models\{User, UserNotification, Profile\UserSetting};
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'user_id')->constrained()->cascadeOnDelete();
            $table->nullableMorphs('notifiable');
            $table->integer('initiator_id');
            $table->integer('amount')->nullable();
            $table->enum('type', UserSetting::NOTIFICATIONS);
            $table->enum('status', UserNotification::STATUSES)->default(
                UserNotification::STATUSES[UserNotification::NEW]
            );
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
        Schema::dropIfExists('user_notifications');
    }
}
