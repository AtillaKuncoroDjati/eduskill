<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsappService
{
    public static function send($phone, $message)
    {
        if (empty($phone)) return false;

        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }

        $url = config('whatsapp.url') . '/api/message';

        $payload = [
            'token' => config('whatsapp.token'),
            'to' => $phone,
            'message' => $message,
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($url, $payload);

        return $response->successful();
    }
}
