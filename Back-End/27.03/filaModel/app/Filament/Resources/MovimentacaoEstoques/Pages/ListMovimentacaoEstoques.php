<?php

namespace App\Filament\Resources\MovimentacaoEstoques\Pages;

use App\Filament\Resources\MovimentacaoEstoques\MovimentacaoEstoqueResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMovimentacaoEstoques extends ListRecords
{
    protected static string $resource = MovimentacaoEstoqueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
