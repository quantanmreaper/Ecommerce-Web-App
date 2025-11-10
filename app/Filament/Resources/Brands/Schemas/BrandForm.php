<?php

namespace App\Filament\Resources\Brands\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
class BrandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
               TextInput::make('name')
                        ->required()
                        ->reactive()
                        ->live(onBlur: true)
                          ->debounce(1000) // Adjust the delay as needed (milliseconds)
                          ->afterStateUpdated(function ($state, $operation, $set) {
                              if ($operation !== 'create'){
                                return;
                              }
                              $set('slug', Str::slug($state));
                          }),
                TextInput::make('slug')
                    ->required(),
                FileUpload::make('image')
                    ->image(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
