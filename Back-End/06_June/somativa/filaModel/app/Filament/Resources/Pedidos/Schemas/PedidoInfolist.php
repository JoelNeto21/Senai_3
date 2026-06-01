<?php

namespace App\Filament\Resources\Pedidos\Schemas;

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
                    ->money('BRL'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
