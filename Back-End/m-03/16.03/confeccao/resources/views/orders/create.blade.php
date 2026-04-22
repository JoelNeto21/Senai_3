<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Registrar Novo Pedido
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="p-8">
                    <form action="{{ route('orders.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div>
                                <label for="data_pedido" class="block text-sm font-bold text-gray-700 mb-1">Data do Pedido</label>
                                <input type="date" name="data_pedido" id="data_pedido" 
                                    value="{{ old('data_pedido', date('Y-m-d')) }}" 
                                    class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm" required>
                                @error('data_pedido') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="data_entrega" class="block text-sm font-bold text-gray-700 mb-1">Previsão de Entrega</label>
                                <input type="date" name="data_entrega" id="data_entrega" 
                                    value="{{ old('data_entrega', now()->addMonth()->format('Y-m-d')) }}"
                                    min="{{ date('Y-m-d') }}" 
                                    class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm" required>
                                @error('data_entrega') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="quantidade" class="block text-sm font-bold text-gray-700 mb-1">Quantidade (Peças)</label>
                                <input type="number" name="quantidade" id="quantidade" value="{{ old('quantidade') }}" 
                                    class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm" 
                                    placeholder="Ex: 50" required>
                                @error('quantidade') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="valor" class="block text-sm font-bold text-gray-700 mb-1">Valor Total</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">R$</span>
                                    </div>
                                    <input type="number" step="0.01" name="valor" id="valor" value="{{ old('valor') }}" 
                                        class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm pl-10" 
                                        placeholder="0,00" required>
                                </div>
                                @error('valor') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="col-span-full">
                                <label for="descricao" class="block text-sm font-bold text-gray-700 mb-1">Descrição / Observações</label>
                                <textarea name="descricao" id="descricao" rows="4" 
                                    class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm" 
                                    placeholder="Detalhes sobre tecido, cores, tamanhos..."
                                    required>{{ old('descricao') }}</textarea>
                                @error('descricao') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-100">
                            <a href="{{ route('orders.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
                                Cancelar
                            </a>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                                Registrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    const dataPedido = document.getElementById('data_pedido');
    const dataEntrega = document.getElementById('data_entrega');

    dataPedido.addEventListener('change', function() {
        // Define o mínimo da entrega como a data selecionada no pedido
        dataEntrega.min = this.value;
        
        // Se a entrega atual for menor que a nova data do pedido, limpa ou ajusta
        if (dataEntrega.value < this.value) {
            dataEntrega.value = this.value;
        }
    });
</script>