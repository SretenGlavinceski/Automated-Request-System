<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\NotificationLog;
use App\Notifications\TicketNotification;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Notifications\Events\NotificationSending;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Support\Facades\Event;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(
            NotificationSending::class,
            function (NotificationSending $event): void {
                if (! $event->notification instanceof TicketNotification) {
                    return;
                }

                $log = NotificationLog::firstOrNew([
                    'tracking_id' => $event->notification->trackingId(),
                    'channel' => $event->channel,
                ]);

                $log->fill([
                    'ticket_id' => $event->notification->ticket()->id,
                    'recipient_id' => $event->notifiable->id,
                    'message' => $event->notification->message(),
                    'status' => 'processing',
                    'attempts' => $log->exists
                        ? $log->attempts + 1
                        : 1,
                    'error_message' => null,
                    'failed_at' => null,
                ]);

                $log->save();
            }
        );

        Event::listen(
            NotificationSent::class,
            function (NotificationSent $event): void {
                if (! $event->notification instanceof TicketNotification) {
                    return;
                }

                NotificationLog::where(
                    'tracking_id',
                    $event->notification->trackingId()
                )
                    ->where('channel', $event->channel)
                    ->update([
                        'status' => 'sent',
                        'sent_at' => now(),
                        'error_message' => null,
                        'failed_at' => null,
                    ]);
            }
        );

        Event::listen(
            NotificationFailed::class,
            function (NotificationFailed $event): void {
                if (! $event->notification instanceof TicketNotification) {
                    return;
                }

                NotificationLog::where(
                    'tracking_id',
                    $event->notification->trackingId()
                )
                    ->where('channel', $event->channel)
                    ->update([
                        'status' => 'failed',
                        'error_message' => 'Notification delivery failed.',
                        'failed_at' => now(),
                    ]);
            }
        );
    }
}
