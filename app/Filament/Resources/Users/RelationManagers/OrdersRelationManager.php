<?php

namespace App\Filament\Resources\Users\RelationManagers;

use App\Filament\Resources\Users\UserResource;
use App\Filament\Resources\Orders\OrderResource;
use Filament\Actions\ViewAction;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    protected static ?string $relatedResource = UserResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('id')
                ->label('Order ID')
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

            ])
            ->actions([
                ViewAction::make()
                    ->url(fn ($record): string => OrderResource::getUrl('view', ['record' => $record])),
            ])
            ->headerActions([
                CreateAction::make(),
            ]);

    }
}
