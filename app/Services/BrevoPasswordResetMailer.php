<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class BrevoPasswordResetMailer
{
    public function send(User $user, string $token): void
    {
        $key = config('services.brevo.key');
        $sender = config('services.brevo.sender');
        if (!$key || !$sender) {
            throw new RuntimeException('El correo de recuperación no está configurado.');
        }

        $url = rtrim(config('app.url'), '/').'/password/reset/'.rawurlencode($token)
            .'?email='.rawurlencode($user->getEmailForPasswordReset());
        $body = 'Solicitaste cambiar tu contraseña. Abre este enlace para continuar: '.$url
            ."\n\nSi no lo solicitaste, ignora este mensaje.";

        Http::withHeaders(['api-key' => $key])
            ->timeout(10)
            ->post('https://api.brevo.com/v3/smtp/email', [
                'sender' => ['email' => $sender, 'name' => config('app.name')],
                'to' => [['email' => $user->getEmailForPasswordReset()]],
                'subject' => 'Restablecer contraseña - '.config('app.name'),
                'textContent' => $body,
                'htmlContent' => '<p>Solicitaste cambiar tu contraseña.</p><p><a href="'.e($url).'">Restablecer contraseña</a></p><p>Si no lo solicitaste, ignora este mensaje.</p>',
            ])->throw();
    }
}
