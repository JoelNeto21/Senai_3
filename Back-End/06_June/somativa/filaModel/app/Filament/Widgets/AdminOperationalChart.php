<?php

namespace App\Filament\Widgets;

use App\Models\Pedido;
use Filament\Widgets\ChartWidget;

class AdminOperationalChart extends ChartWidget
{
    protected static bool $isLazy = false;

    protected ?string $pollingInterval = null;

    protected ?string $heading = 'Pedidos por status';

    protected ?string $description = 'Distribuição operacional dos pedidos cadastrados.';

    protected string $color = 'info';

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        $statuses = ['Pendente', 'Em Produção', 'Entregue'];
        $aliases = [
            'Em Produção' => ['Em Produção', 'Em Producao', 'Em ProduÃ§Ã£o'],
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Pedidos',
                    'data' => collect($statuses)
                        ->map(fn (string $status) => Pedido::query()
                            ->whereIn('status', $aliases[$status] ?? [$status])
                            ->count())
                        ->all(),
                ],
            ],
            'labels' => ['Pendente', 'Em produção', 'Entregue'],
        ];
    }
}
