<?php

namespace App\Filament\Resources\Fornecedors\Schemas;

use App\Support\BrazilianFormat;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class FornecedorInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nome'),
                TextEntry::make('cnpj')
                    ->formatStateUsing(fn ($state) => BrazilianFormat::cpfCnpj($state))
                    ->placeholder('-'),
                TextEntry::make('email')
                    ->label('E-mail')
                    ->placeholder('-'),
                TextEntry::make('telefone')
                    ->formatStateUsing(fn ($state) => BrazilianFormat::phone($state))
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('-'),
            ]);
    }
}
