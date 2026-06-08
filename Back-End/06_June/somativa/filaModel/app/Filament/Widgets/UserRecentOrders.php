<?php

namespace App\Filament\Widgets;

use App\Models\Pedido;
use Filament\Widgets\Widget;

class UserRecentOrders extends Widget
{
    protected static bool $isLazy = false;

    protected string $view = 'filament.widgets.user-recent-orders';

    protected int | string | array $columnSpan = 'full';

    public function getViewData(): array
    {
        return [
            'pedidos' => Pedido::query()
                ->with('cliente')
                ->whereHas('cliente', fn ($query) => $query->where('email', auth()->user()?->email))
                ->latest()
                ->limit(5)
                ->get(),
        ];
    }
}
