<?php

namespace App\Console\Commands;

use App\Models\Goal;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class UpdateGoalStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'goals:update_status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Обновление статуса целей';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        //присвоить статус просроченной
        Goal::whereIn('status', [Goal::STATUSES[Goal::STATUS_IN_PROGRESS], Goal::STATUSES[Goal::STATUS_ENDING]])
            ->where('deadline_at', '<', now())
            ->each(function ($goal) {
                $goal->progress >= Goal::COMPLETE_MARK
                    ? $goal->update(['status' => Goal::STATUSES[Goal::STATUS_COMPLETE]])
                    : $goal->update(['status' => Goal::STATUSES[Goal::STATUS_OVERDUE]]);
            });

        //присвоить статус завершающейся
        Goal::whereStatus(Goal::STATUSES[Goal::STATUS_IN_PROGRESS])
            ->whereBetween('deadline_at', [now()->startOfDay(), now()->addDay()->endOfDay()])
            ->update(['status' => Goal::STATUSES[Goal::STATUS_ENDING]]);

        return CommandAlias::SUCCESS;
    }
}
