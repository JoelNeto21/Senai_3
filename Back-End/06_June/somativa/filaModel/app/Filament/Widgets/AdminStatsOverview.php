<?php

namespace App\Filament\Widgets;

use App\Models\Cliente;
use App\Models\Fornecedor;
use App\Models\Pedido;
use App\Models\Produto;
use App\Models\User;
use App\Support\BrazilianFormat;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsOverview extends StatsOverviewWidget
{
    protected static bool $isLazy = false;

    protected ?string $pollingInterval = null;

    protected ?string $heading = 'Resumo executivo';

    protected ?string $description = 'Indicadores calculados com os dados reais cadastrados no sistema.';

    protected function getStats(): array
    {
        $startOfMonth = now()->startOfMonth();
        $lowStock = Produto::query()->where('quantidade', '<=', 5)->count();
        $monthlyRevenue = (float) Pedido::query()
            ->where('created_at', '>=', $startOfMonth)
            ->sum('valor_total');

        return [
            Stat::make('Usuários', User::query()->count())
                ->description(User::query()->where('created_at', '>=', $startOfMonth)->count() . ' novos neste mês')
                ->descriptionColor('success')
                ->icon(Heroicon::Users),
            Stat::make('Clientes', Cliente::query()->count())
                ->description(Fornecedor::query()->count() . ' fornecedores cadastrados')
                ->icon(Heroicon::Identification),
            Stat::make('Pedidos', Pedido::query()->count())
                ->description(Pedido::query()->where('status', 'Pendente')->count() . ' pendentes')
                ->descriptionColor('warning')
                ->icon(Heroicon::ClipboardDocumentList),
            Stat::make('Receita do mês', BrazilianFormat::currency($monthlyRevenue))
                ->description('Valor total dos pedidos no mês atual')
                ->descriptionColor('success')
                ->icon(Heroicon::Banknotes),
            Stat::make('Produtos', Produto::query()->count())
                ->description($lowStock . ' com estoque baixo')
                ->descriptionColor($lowStock > 0 ? 'danger' : 'success')
                ->icon(Heroicon::Cube),
        ];
    }
}
