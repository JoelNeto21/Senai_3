<?php

namespace App\Listeners;

use App\Events\PedidoCriado;
use App\Mail\PedidoRegistrado;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EnviarEmailPedidoRegistrado
{
    /**
     * Handle the event.
     */
    public function handle(PedidoCriado $event): void
    {
        $pedido = $event->pedido;
        $cliente = $pedido->cliente;

        if (! $cliente || ! $cliente->email) {
            Log::warning('Pedido #' . $pedido->id . ' sem email do cliente para notificação.');
            return;
        }

        Mail::to($cliente->email)
            ->send(new PedidoRegistrado($pedido));
    }
}