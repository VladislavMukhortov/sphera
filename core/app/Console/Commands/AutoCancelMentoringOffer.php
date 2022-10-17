<?php

namespace App\Console\Commands;

use App\Events\OfferAutoCanceledEvent;
use App\Models\Offer;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class AutoCancelMentoringOffer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'offers:auto-cancel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Отмена запросов/предложений менторства через 24 часа';

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
        Offer::where('created_at', '<=', now()->subDay())->each(fn($offer) => event(new OfferAutoCanceledEvent($offer)));

        return CommandAlias::SUCCESS;
    }
}
