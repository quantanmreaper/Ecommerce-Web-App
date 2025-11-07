<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Filament\Forms\Components\Select;
use App\Models\Category;
use App\Models\Product;
use Filament\Forms\Set;


class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('brand_id')
                    ->label('Brand')
                    ->relationship('brand', 'name') // assumes Product model has a 'brand' relationship
                    ->searchable()
                    ->preload()
                    ->required(),
                    TextInput::make('name')
                          ->required()
                          ->reactive()
                          ->maxLength(255)
                          ->live(onBlur: true)
                          ->debounce(1000) // Adjust the delay as needed (milliseconds)
                          ->afterStateUpdated(function ($state, $operation, $set) {
                              if ($operation !== 'create'){
                                return;
                              }
                              $set('slug', Str::slug($state));
                          }),
                TextInput::make('slug')
                    ->required()
                    ->disabled()
                    ->dehydrated()
                    ->unique(Product::class, 'slug', ignoreRecord: true),
                FileUpload::make('images')
                    ->multiple()
                    ->directory('products')
                    ->maxFiles(5)
                    ->reorderable()
                    ->columnSpanFull(),
                MarkdownEditor::make('description')
                    ->columnSpanFull()
                    ->fileAttachmentsDirectory('products'),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('KSH'),
                Toggle::make('is_active')
                    ->required(),
                Toggle::make('is_featured')
                    ->required(),
                Toggle::make('in_stock')
                    ->required(),
                Toggle::make('on_sale')
                    ->required(),
            ]);
    }
}
