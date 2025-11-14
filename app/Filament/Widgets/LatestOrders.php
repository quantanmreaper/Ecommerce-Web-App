<?php

namespace App\Filament\Widgets;

use Filament\Actions\BulkActionGroup;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Order;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\Orders\OrderResource;

class LatestOrders extends TableWidget
{

    protected static bool $isLazy = false;
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 2;
    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Order::query())
            ->columns([
                TextColumn::make('id')
                ->label('Order ID')
                ->searchable(),
                TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->label('Status')
                    ->sortable()
                    ->color(fn ($state) => match ($state) {
                        'new' => 'info',
                        'processing' => 'warning',
                        'shipped' => 'success',
                        'delivered' => 'success',
                        'canceled' => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn ($state) => match ($state) {
                        'new' => 'heroicon-m-sparkles',
                        'processing' => 'heroicon-m-arrow-path',
                        'shipped' => 'heroicon-m-truck',
                        'delivered' => 'heroicon-m-check-circle',
                        'canceled' => 'heroicon-m-x-circle',
                        //default => 'gray',
                    })
                    ->sortable(),
                    TextColumn::make('total_amount')
                    ->label('Total Amount')
                    ->money('KSH', true)
                    ->sortable(),
                    TextColumn::make('payment_status')
                    ->sortable()
                    ->searchable(),
                    TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Order Date')
                    ->sortable(),
                TextColumn::make('user_id')
                    ->label('Action')
                    ->formatStateUsing(fn () => 'View Order')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record): string => OrderResource::getUrl('view', ['record' => $record]))
                    ->color('primary'),
                //
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
