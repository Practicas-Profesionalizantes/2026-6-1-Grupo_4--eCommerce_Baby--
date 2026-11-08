<?php

namespace App\Support;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AvisoAdmin
{
    public static function enviar(string $asunto, array $lineas, ?string $enlace = null): void
    {
        try {
            $destino = config('mail.from.address');

            if (!$destino) {
                return;
            }

            $cuerpo = '<p>Hola,</p>';

            foreach ($lineas as $linea) {
                $cuerpo .= '<p>' . e($linea) . '</p>';
            }

            if ($enlace) {
                $cuerpo .= '<p><a href="' . e($enlace) . '">Abrir en el panel</a></p>';
            }

            $cuerpo .= '<p>Kiddy Shop</p>';

            Mail::html($cuerpo, function ($m) use ($destino, $asunto) {
                $m->to($destino)->subject($asunto);
            });
        } catch (\Throwable $e) {
            Log::warning('No se pudo enviar el mail al admin (' . $asunto . '): ' . $e->getMessage());
        }
    }
}