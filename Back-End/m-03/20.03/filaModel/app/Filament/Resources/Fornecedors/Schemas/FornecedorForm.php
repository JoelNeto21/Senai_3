<?php

namespace App\Filament\Resources\Fornecedors\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class FornecedorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nome')
                    ->required(),
                TextInput::make('cnpj'),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('telefone')
                    ->tel(),
            ]);
    }
}
