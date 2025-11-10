<?php
namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Set;
use Filament\Forms\Get;
use App\Models\Product;
use Filament\Forms\Components\Hidden;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Customer')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('payment_method')
                    ->options([
                        'stripe' => 'Stripe',
                        'cod' => 'Cash on Delivery',
                        'paypal' => 'PayPal',
                        'bank_transfer' => 'Bank Transfer',
                    ])
                    ->required(),

                Select::make('payment_status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'failed' => 'Failed',
                    ])
                    ->default('pending')
                    ->required(),

                ToggleButtons::make('status')
                   ->options([
                    'new' => 'New',
                    'processing' => 'Processing',
                    'shipped' => 'Shipped',
                    'delivered' => 'Delivered',
                    'canceled' => 'Canceled',
                   ])
                   ->colors([
                    'new' => 'primary',
                    'processing' => 'warning',
                    'shipped' => 'info',
                    'delivered' => 'success',
                    'canceled' => 'danger',
                   ])
                    ->icons([
                    'new' => 'heroicon-m-sparkles',
                    'processing' => 'heroicon-m-arrow-path',
                    'shipped' => 'heroicon-m-truck',
                    'delivered' => 'heroicon-m-check-circle',
                    'canceled' => 'heroicon-m-x-circle',
                   ])
                    ->inline()
                    ->required()
                    ->default('new'),

                Select::make('currency')
                    ->options([
                        'ksh' => 'KSH',
                        'usd' => 'USD',
                        'eur' => 'EUR',
                        'gbp' => 'GBP',
                    ])
                    ->default('ksh')
                    ->required(),

                TextInput::make('shipping_amount')
                    ->numeric()
                    ->default(0),
                    //->required(),

                Select::make('shipping_method')
                    ->options([
                        'g4s' => 'G4S',
                        'dhl' => 'DHL',
                        'fargowells' => 'Fargowells',
                    ])
                    ->default('g4s')
                    ->required(),

                Textarea::make('notes')
                    ->columnSpanFull(),

                Repeater::make('items')
                    ->relationship('items')
                    ->schema([
                        Select::make('product_id')
                            ->relationship('product', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->distinct()
                            ->columnSpan(2)
                            ->reactive()
                            ->afterStateUpdated(fn ($state,  $set) => $set('unit_amount', Product::find($state)?->price ?? 0))
                            ->afterStateUpdated(fn ($state,  $set) => $set('total_amount', Product::find($state)?->price ?? 0))
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems(),

                        TextInput::make('quantity')
                            ->required()
                            ->numeric()
                            ->columnSpan(2)
                            ->minValue(1)
                            ->reactive()
                            ->afterStateUpdated(function ($state,  $set,  $get) {
                                $unitAmount = $get('unit_amount') ?? 0;
                                $set('total_amount', $unitAmount * $state);
                            })
                            ->default(1),

                        TextInput::make('unit_amount')
                            ->required()
                            ->numeric()
                            ->columnSpan(3)
                            ->dehydrated()
                            ->disabled(),

                        TextInput::make('total_amount')
                            ->required()
                            ->numeric()
                            ->dehydrated()
                            ->columnSpan(3)
                            ->disabled(),

                    ])
                    ->columns(12)
                    ->columnSpanFull(),

                Placeholder::make('grand_total_placeholder')
                    ->label('Grand Total')
                    ->content(function ( $get): string {
                        $total = 0;
                        $items = $get('items') ?? [];

                        foreach ($items as $item) {
                            $total += $item['total_amount'] ?? 0;
                        }
                        $shippingAmount = $get('shipping_amount') ?? 0;
                        $total += $shippingAmount;

                        return 'KSH ' . number_format($total, 2);
                    }),

                    Hidden::make('grand_total')
                    ->default(0),
            ]);
    }
}
