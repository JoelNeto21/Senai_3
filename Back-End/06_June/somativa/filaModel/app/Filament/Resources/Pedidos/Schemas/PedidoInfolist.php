<?php

namespace App\Filament\Resources\Pedidos\Schemas;

use App\Support\BrazilianFormat;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PedidoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('cliente.nome')
                    ->label('Cliente'),
                TextEntry::make('status'),
                TextEntry::make('valor_total')
                    ->label('Valor Total')
                    ->formatStateUsing(fn ($state) => BrazilianFormat::currency($state)),
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
