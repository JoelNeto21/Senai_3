<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AdminFinanceChart;
use App\Filament\Widgets\AdminOperationalChart;
use App\Filament\Widgets\AdminRecentActivity;
use App\Filament\Widgets\AdminStatsOverview;
use App\Filament\Widgets\UserQuickSummary;
use App\Filament\Widgets\UserRecentOrders;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationLabel = 'Painel';

    protected static ?string $title = 'Painel';

    public function getWidgets(): array
    {
        if (auth()->user()?->hasRole('Admin')) {
            return [
                AdminStatsOverview::class,
                AdminFinanceChart::class,
                AdminOperationalChart::class,
                AdminRecentActivity::class,
            ];
        }

        return [
            UserQuickSummary::class,
            UserRecentOrders::class,
        ];
    }

    public function getColumns(): int | array
    {
        return [
            'default' => 1,
            'lg' => 2,
        ];
    }
}
