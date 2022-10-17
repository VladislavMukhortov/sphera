<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\{Carbon, Facades\Event};

class ActivityEvent extends Event
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * Тип активности
     *
     * @var string
     */
    public string $type;

    /**
     * Пользователь
     *
     * @var User
     */
    public User $user;

    /**
     * Временная метка события
     *
     * @var Carbon
     */
    public Carbon $date;

    /**
     * Обратное списание баллов за действие(в случае отмены и т.д.)
     *
     * @var bool
     */
    public bool $revert;

    /**
     * Create a new event instance.
     *
     * @param User $user
     * @param string $type
     * @param bool $revert
     */
    public function __construct(User $user, string $type, bool $revert = false)
    {
        $this->user = $user;
        $this->type = $type;
        $this->date = now();
        $this->revert = $revert;
    }
}
