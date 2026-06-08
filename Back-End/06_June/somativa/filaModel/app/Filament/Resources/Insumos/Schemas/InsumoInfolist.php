<?php

namespace App\Filament\Resources\Insumos\Schemas;

use App\Support\BrazilianFormat;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class InsumoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nome'),
                TextEntry::make('unidade_medida')
                    ->label('Un. de Medida')
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'unidade' => 'un',
                        'metro' => 'm',
                        'centimetro' => 'cm',
                        'milimetro' => 'mm',
                        default => $state ?? '-',
                    }),
                TextEntry::make('preco_custo')
                    ->label('Preço de Custo')
                    ->formatStateUsing(fn ($state) => BrazilianFormat::currency($state)),
                TextEntry::make('estoque'),
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
