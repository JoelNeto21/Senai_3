<?php

namespace App\Filament\Widgets;

use App\Models\Pedido;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserQuickSummary extends StatsOverviewWidget
{
    protected static bool $isLazy = false;

    protected ?string $pollingInterval = null;

    protected ?string $heading = 'Meu resumo';

    protected ?string $description = 'Informações principais da sua conta.';

    protected function getStats(): array
    {
        $email = auth()->user()?->email;
        $pedidos = Pedido::query()->whereHas('cliente', fn ($query) => $query->where('email', $email));

        return [
            Stat::make('Nome', auth()->user()?->name ?? '-')
                ->description('Dados da conta autenticada')
                ->icon(Heroicon::UserCircle),
            Stat::make('Pedidos vinculados', (clone $pedidos)->count())
                ->description((clone $pedidos)->where('status', 'Pendente')->count() . ' pendentes')
                ->descriptionColor('warning')
                ->icon(Heroicon::ClipboardDocumentList),
            Stat::make('E-mail', $email ?? '-')
                ->description('Canal usado para notificações')
                ->icon(Heroicon::Envelope),
        ];
    }
}
