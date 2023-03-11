<?php

namespace App\Notifications;

use App\Services\Contracts\InvoicesServiceContract;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use NotificationChannels\Telegram\TelegramFile;
use NotificationChannels\Telegram\TelegramMessage;

class OrderCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(public InvoicesServiceContract $invoicesService)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return $notifiable?->user?->telegram_id ? ["telegram", "mail"] : ['mail'];
    }

    public function toTelegram($notifiable)
    {
        return TelegramMessage::create()
            ->to($notifiable->user->telegram_id)
            ->content("Hello {$notifiable->user->fullName}")
            ->line("\nYour order was created!");
        //            ->button('See your wish list', url('account/wishlist'));
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
//        dd($notifiable);
//        dump(self::class);
//        $result= 4 / 0;
//        logs()->info(self::class . ' has started');
//        dd($this->invoicesService->generate($notifiable)->filename);
        logs()->info(self::class);
//        logs()->info($this->invoicesService->generate($notifiable)->url());
//        logs()->info('NOTIFIABLE: ' . $notifiable::class);
//        logs()->info('Invoice: ' . $this->invoicesService::class);
//        logs()->info('PATH - ' . $this->invoicesService->generate($notifiable)->filename);
        $invoice = $this->invoicesService->generate($notifiable);

        return (new MailMessage())
                    ->greeting("Hello {$notifiable->user->fullName}")
                    ->line('Your order was created!')
                    ->line('You can read invoice data in attached file')
                    ->attach(Storage::disk('public')->path($invoice->filename), [
        'as' => 'name.pdf',
        'mime' => 'application/pdf',
    ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
