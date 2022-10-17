<?php

namespace App\Events\Auth;

use App\Models\User;
use Illuminate\Broadcasting\{InteractsWithSockets, PrivateChannel};
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SignUpEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public ?string $ip = null;

    /**
     * @param User $user
     * @param string|null $ip
     */
    public function __construct(public User $user, string $ip = null)
    {
        $this->ip = $ip;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return PrivateChannel|array
     */
    public function broadcastOn(): PrivateChannel|array
    {
        return new PrivateChannel('channel-name');
    }
}
