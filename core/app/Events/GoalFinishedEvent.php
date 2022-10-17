<?php

namespace App\Events;

use App\Models\Goal;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Event;

class GoalFinishedEvent extends Event
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * @var Goal
     */
    public Goal $goal;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Goal $goal)
    {
        $this->goal = $goal;
    }
}
