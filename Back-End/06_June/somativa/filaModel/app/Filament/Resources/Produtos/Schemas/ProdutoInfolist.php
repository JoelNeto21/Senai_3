<?php

namespace App\Filament\Resources\Produtos\Schemas;

use App\Support\BrazilianFormat;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProdutoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nome'),
                TextEntry::make('categoria')
                    ->label('Categoria')
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'eletronicos' => 'Eletrônicos',
                        'vestuario' => 'Vestuário',
                        'alimentos' => 'Alimentos',
                        default => $state ?? '-',
                    }),
                TextEntry::make('descricao')
                    ->placeholder('-'),
                TextEntry::make('valor_unitario')
                    ->label('Preço Unitário')
                    ->formatStateUsing(fn ($state) => BrazilianFormat::currency($state)),
                TextEntry::make('quantidade')
                    ->badge()
                    ->color(fn (int $state): string => $state <= 5 ? 'danger' : 'gray')
                    ->label('Quantidade em Estoque'),
                TextEntry::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('-'),
            ]);
    }
}
