<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gerenciamento de Pedidos
            </h2>
            <a href="{{ route('orders.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 transition ease-in-out duration-150">
                + Novo Pedido
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 shadow-sm rounded-r-lg flex items-center justify-between" role="alert">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 shadow-sm rounded-r-lg flex items-center" role="alert">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            @endif
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($orders as $order)
                    <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:shadow-md transition-all relative flex flex-col justify-between">
                        
                        <div class="flex justify-between items-start mb-4">
                            <span class="text-[10px] font-bold uppercase text-gray-400 tracking-widest">
                                #{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}
                            </span>
                            
                            {{-- Badge de Status --}}
                            @php
                                $statusColor = [
                                    'Pendente' => 'bg-yellow-100 text-yellow-700',
                                    'Em Produção' => 'bg-blue-100 text-blue-700',
                                    'Entregue' => 'bg-green-100 text-green-700',
                                    'Cancelado' => 'bg-red-100 text-red-700'
                                ][$order->status] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <span class="px-2 py-1 rounded text-[10px] font-black uppercase {{ $statusColor }}">
                                {{ $order->status ?? 'Pendente' }}
                            </span>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-black text-gray-900 uppercase truncate mb-1">
                                {{ $order->descricao }}
                            </h3>
                            <div class="flex items-center gap-4 text-xs font-mono">
                                <span class="text-blue-600"><b>QTD:</b> {{ $order->quantidade }}</span>
                                <span class="text-green-600 font-bold">R$ {{ number_format($order->valor, 2, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="space-y-2 py-4 border-t border-gray-100 text-[11px]">
                            <div class="flex justify-between">
                                <span class="text-gray-500 uppercase font-bold">Data Pedido:</span>
                                <span class="text-gray-900 font-black">{{ \Carbon\Carbon::parse($order->data_pedido)->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500 uppercase font-bold">Previsão Entrega:</span>
                                <span class="text-blue-700 font-black">{{ \Carbon\Carbon::parse($order->data_entrega)->format('d/m/Y') }}</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-4 border-t border-gray-100">
                            <a href="{{ route('orders.edit', $order->id) }}" class="p-2 text-gray-400 hover:text-blue-600 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </a>
                            
                            <form action="{{ route('orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Excluir este pedido permanentemente?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-gray-400 hover:text-red-600 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            let alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(function(alert) {
                alert.style.transition = "opacity 0.5s ease";
                alert.style.opacity = "0";
                setTimeout(() => alert.remove(), 500);
            });
        }, 3000); 
    });
</script>