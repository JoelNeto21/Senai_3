<?php

namespace App\Filament\Resources\Produtos\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProdutoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nome'),
                TextEntry::make('categoria'),
                TextEntry::make('descricao')
                    ->placeholder('-'),
                TextEntry::make('valor_unitario')
                    ->label('Preço Unitário')
                    ->money('BRL'),
                TextEntry::make('quantidade')
                    ->badge()
                    ->color(fn (int $state): string => $state <= 5 ? 'danger' : 'gray')
                    ->label('Quantidade em Estoque'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
