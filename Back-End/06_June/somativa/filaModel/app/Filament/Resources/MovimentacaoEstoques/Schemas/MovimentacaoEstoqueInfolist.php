<?php

namespace App\Filament\Resources\MovimentacaoEstoques\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class MovimentacaoEstoqueInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('produto.nome')
                    ->label('Produto'),
                TextEntry::make('movimentacao')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'Entrada' ? 'success' : 'warning'),
                TextEntry::make('quantidade'),
                TextEntry::make('descricao')
                    ->placeholder('-'),
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
