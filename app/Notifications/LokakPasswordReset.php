<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class LokakPasswordReset extends Notification implements ShouldQueue
{
    use Queueable;

    public $token;
    public $url;

    public function __construct($token, $url)
    {
        $this->token = $token;
        $this->url = $url;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(Lang::get('Pemulihan Kata Sandi - Lokak Begawe'))
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line(Lang::get('Kami menerima permintaan untuk mengatur ulang kata sandi akun Anda di Lokak Begawe.'))
            ->action(Lang::get('Atur Ulang Kata Sandi'), $this->url)
            ->line(Lang::get('Tautan pemulihan kata sandi ini akan kedaluwarsa dalam 60 menit.'))
            ->line(Lang::get('Jika Anda tidak merasa melakukan permintaan ini, abaikan saja email ini.'))
            ->salutation('Terima kasih, Team Lokak Begawe');
    }
}