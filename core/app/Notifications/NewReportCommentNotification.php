<?php

namespace App\Notifications;

use App\Models\Report;
use App\Notifications\Traits\FcmNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewReportCommentNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use FcmNotification;

    private string $title;
    private ?string $body;

    /**
     * Create a new notification instance.
     *
     * @param Report $report
     */
    public function __construct(Report $report)
    {
        $this->title = 'Новый комментарий к отчету';

        $this->body = $report->goal
            ? 'По цели: ' . $report->goal->title
            : 'По дню: ' . $report->created_at->format('d-m-Y');
    }
}
