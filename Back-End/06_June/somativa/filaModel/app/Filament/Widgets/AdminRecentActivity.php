<?php

namespace App\Filament\Widgets;

use App\Models\MovimentacaoEstoque;
use App\Models\Pedido;
use App\Models\Produto;
use Filament\Widgets\Widget;

class AdminRecentActivity extends Widget
{
    protected static bool $isLazy = false;

    protected string $view = 'filament.widgets.admin-recent-activity';

    protected int | string | array $columnSpan = 'full';

    public function getViewData(): array
    {
        return [
            'movimentacoes' => MovimentacaoEstoque::query()
                ->with('produto')
                ->latest()
                ->limit(5)
                ->get(),
            'pedidosPendentes' => Pedido::query()
                ->with('cliente')
                ->where('status', 'Pendente')
                ->latest()
                ->limit(5)
                ->get(),
            'estoqueBaixo' => Produto::query()
                ->where('quantidade', '<=', 5)
                ->orderBy('quantidade')
                ->limit(5)
                ->get(),
        ];
    }
}
