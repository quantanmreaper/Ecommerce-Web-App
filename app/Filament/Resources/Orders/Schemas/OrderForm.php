<?php
namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Repeater;
use App\Models\Product;


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
                            ->columnSpan(4)
                            ->afterStateUpdated(function ($state, $set, $get) {
                                $price = Product::find($state)?->price ?? 0;
                                $set('unit_amount', $price);
                                $quantity = $get('quantity') ?? 1;
                                $set('total_amount', $price * $quantity);
                            })
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems(),

                        TextInput::make('quantity')
                            ->required()
                            ->numeric()
                            ->columnSpan(2)
                            ->minValue(1)
                            ->afterStateUpdated(function ($state, $set, $get) {
                                $unitAmount = $get('unit_amount') ?? 0;
                                $set('total_amount', $unitAmount * $state);
                            })
                            ->default(1),

                        TextInput::make('unit_amount')
                            ->label('Price')
                            ->required()
                            ->numeric()
                            ->columnSpan(3)
                            ->disabled()
                            ->dehydrated(),

                        TextInput::make('total_amount')
                            ->label('Total')
                            ->required()
                            ->numeric()
                            ->columnSpan(3)
                            ->disabled()
                            ->dehydrated(),

                    ])
                    ->columns(12)
                    ->columnSpanFull(),
            ]);
    }
}
