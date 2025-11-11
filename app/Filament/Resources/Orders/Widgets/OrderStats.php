<?php

namespace App\Filament\Resources\Orders\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Order;
class OrderStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $currency = 'KSH';
        $symbol = $currency === 'KSH' ? 'KSH ' : '$';
        $avgOrderValue = Order::query()->avg('total_amount');
        return [
            Stat::make('New Orders', Order::query()->where('status', 'new')->count()),
            Stat::make('Processing Orders', Order::query()->where('status', 'processing')->count()),
            Stat::make('Shipped Orders', Order::query()->where('status', 'shipped')->count()),
            Stat::make('Average Order Value', fn () => $symbol . number_format($avgOrderValue, 2)),
        ];
    }
}
