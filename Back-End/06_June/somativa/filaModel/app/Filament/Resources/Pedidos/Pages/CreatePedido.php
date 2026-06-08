<?php

namespace App\Filament\Resources\Pedidos\Pages;

use App\Filament\Resources\Pedidos\PedidoResource;
use App\Models\Cliente;
use Filament\Resources\Pages\CreateRecord;

class CreatePedido extends CreateRecord
{
    protected static string $resource = PedidoResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (auth()->user()?->hasRole('Cliente')) {
            $data['cliente_id'] = Cliente::query()
                ->where('email', auth()->user()?->email)
                ->value('id');
            $data['status'] = 'Pendente';
        }

        return $data;
    }

    protected function afterCreate(): void{
        $this->record->recalculateTotal();
    }
}
