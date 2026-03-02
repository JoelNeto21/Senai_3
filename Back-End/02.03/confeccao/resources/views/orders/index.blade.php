<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gerenciamento de Pedidos</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($orders as $order)
                    <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:shadow-md transition-all duration-200">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-xs font-bold px-2.5 py-1 bg-gray-100 text-gray-600 rounded-lg uppercase">Qtd: {{ $order->quantidade }}</span>
                            <span class="text-lg font-black text-green-600">R$ {{ number_format($order->valor, 2, ',', '.') }}</span>
                        </div>

                        <div class="mb-6">
                            <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Detalhes do Pedido</h4>
                            <p class="text-sm text-gray-700 leading-relaxed line-clamp-2 h-10">{{ $order->descricao }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-2 pt-4 border-t border-gray-100 text-center">
                            <div class="border-r border-gray-100">
                                <p class="text-[9px] font-bold text-gray-400 uppercase">Data Pedido</p>
                                <p class="text-xs font-semibold text-gray-700">{{ \Carbon\Carbon::parse($order->data_pedido)->format('d/m/Y') }}</p>
                            </div>
                            <div>
                                <p class="text-[9px] font-bold text-orange-500 uppercase">Previsão</p>
                                <p class="text-xs font-bold text-gray-900">{{ \Carbon\Carbon::parse($order->data_entrega)->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>