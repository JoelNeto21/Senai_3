<?php

namespace App\Filament\Resources\Insumos\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InsumoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nome')
                    ->required(),
                TextInput::make('unidade_medida')
                    ->required(),
                TextInput::make('preco_custo')
                    ->required(),
                TextInput::make('estoque')
                    ->required(),
            ]);
    }
}
