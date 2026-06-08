<?php

namespace App\Filament\Widgets;

use App\Models\Pedido;
use Carbon\CarbonImmutable;
use Filament\Widgets\ChartWidget;

class AdminFinanceChart extends ChartWidget
{
    protected static bool $isLazy = false;

    protected ?string $pollingInterval = null;

    protected ?string $heading = 'Receita mensal';

    protected ?string $description = 'Linha com o valor total de pedidos nos últimos 6 meses. Passe o mouse sobre os pontos para ver os valores.';

    protected string $color = 'success';

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $months = collect(range(5, 0))->map(fn (int $monthsAgo) => CarbonImmutable::now()->subMonths($monthsAgo));

        return [
            'datasets' => [
                [
                    'label' => 'Receita em R$',
                    'data' => $months
                        ->map(fn (CarbonImmutable $date) => (float) Pedido::query()
                            ->whereYear('created_at', $date->year)
                            ->whereMonth('created_at', $date->month)
                            ->sum('valor_total'))
                        ->all(),
                    'fill' => true,
                    'tension' => 0.35,
                ],
            ],
            'labels' => $months
                ->map(fn (CarbonImmutable $date) => ucfirst($date->locale('pt_BR')->translatedFormat('M/y')))
                ->all(),
        ];
    }
}
