<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar Pedido #{{ $order->id }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-8">
                <form action="{{ route('orders.update', $order->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Status do Pedido</label>
                            <select name="status" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500">
                                <option value="Pendente" {{ $order->status == 'Pendente' ? 'selected' : '' }}>Pendente</option>
                                <option value="Em Produção" {{ $order->status == 'Em Produção' ? 'selected' : '' }}>Em Produção</option>
                                <option value="Entregue" {{ $order->status == 'Entregue' ? 'selected' : '' }}>Entregue</option>
                                <option value="Cancelado" {{ $order->status == 'Cancelado' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Valor Total (R$)</label>
                            <input type="number" step="0.01" name="valor" value="{{ old('valor', $order->valor) }}" class="w-full border-gray-300 rounded-lg shadow-sm" required>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Data do Pedido</label>
                            <input type="date" name="data_pedido" value="{{ old('data_pedido', $order->data_pedido) }}" class="w-full border-gray-300 rounded-lg shadow-sm" required>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Previsão de Entrega</label>
                            <input type="date" name="data_entrega" value="{{ old('data_entrega', $order->data_entrega) }}" class="w-full border-gray-300 rounded-lg shadow-sm" required>
                        </div>

                        <div class="col-span-full">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Quantidade de Peças</label>
                            <input type="number" name="quantidade" value="{{ old('quantidade', $order->quantidade) }}" class="w-full border-gray-300 rounded-lg shadow-sm" required>
                        </div>

                        <div class="col-span-full">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Descrição</label>
                            <textarea name="descricao" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm" required>{{ old('descricao', $order->descricao) }}</textarea>
                        </div>
                    </div>

                    <div class="flex justify-end gap-4 pt-6 border-t">
                        <a href="{{ route('orders.index') }}" class="text-sm font-medium text-gray-600 mt-2">Cancelar</a>
                        <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-6 rounded-lg hover:bg-blue-700">Atualizar Pedido</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>