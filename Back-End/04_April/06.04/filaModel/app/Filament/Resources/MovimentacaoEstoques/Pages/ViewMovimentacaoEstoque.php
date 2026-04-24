<?php

namespace App\Filament\Resources\MovimentacaoEstoques\Pages;

use App\Filament\Resources\MovimentacaoEstoques\MovimentacaoEstoqueResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMovimentacaoEstoque extends ViewRecord
{
    protected static string $resource = MovimentacaoEstoqueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
