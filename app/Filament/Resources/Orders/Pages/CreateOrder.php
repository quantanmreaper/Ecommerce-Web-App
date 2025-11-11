<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function afterCreate(): void
    {
        // Calculate total from saved items
        $order = $this->record;
        $total = $order->items->sum('total_amount');
        $total += $order->shipping_amount ?? 0;
        
        $order->update(['total_amount' => $total]);
    }
}
