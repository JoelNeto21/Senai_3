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
                    <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:shadow-md transition-all duration-200 relative overflow-hidden flex flex-col justify-between">
                        
                        <div class="flex justify-end items-start">
                            <span class="text-[10px] font-bold uppercase truncate text-gray-400 tracking-widest">
                                #{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}
                            </span>
                        </div>

                        <div class="mb-6">
                            <div class="flex items-center gap-2 mb-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-700 shrink-0">
                                    <rect x="1" y="3" width="15" height="13"></rect>
                                    <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                                    <circle cx="5.5" cy="18.5" r="2.5"></circle>
                                    <circle cx="18.5" cy="18.5" r="2.5"></circle>
                                </svg>
                                
                                <h3 class="text-lg font-black text-gray-900 uppercase truncate">
                                    Pedido
                                </h3>
                            </div>
                            <p class="text-xs text-gray-500 line-clamp-2 h-8 leading-relaxed">
                                {{ $order->descricao }}
                            </p>
                        </div>

                        <div class="flex items-center justify-between pt-4 border-t border-gray-100 mt-auto">
                            
                            <div class="flex items-center gap-1">
                                <b class="font-black text-gray-900 text-[10px] tracking-wider">Qtd.:</b>
                                <span class="text-sm font-mono font-black text-blue-600">
                                    {{ str_pad($order->quantidade, 2, '0', STR_PAD_LEFT) }}
                                </span>
                            </div>

                            <div class="flex items-center gap-1">
                                <b class="font-black text-gray-900 text-[10px] tracking-wider">Pedido:</b>
                                <span class="text-sm font-mono font-black text-blue-600">
                                    {{ \Carbon\Carbon::parse($order->data_pedido)->format('d/m/y') }}
                                </span>
                            </div>

                            <div class="flex items-center gap-1">
                                <b class="font-black text-gray-900 text-[10px] tracking-wider">Entrega:</b>
                                <span class="text-sm font-mono font-black text-blue-600">
                                    {{ \Carbon\Carbon::parse($order->data_entrega)->format('d/m/y') }}
                                </span>
                            </div>

                            <div class="flex items-center gap-1">
                                <b class="font-black text-gray-900 text-[10px] tracking-wider">Total:</b>
                                <span class="text-sm font-mono font-black text-blue-600">
                                    R$ {{ number_format($order->valor, 2, ',', '.') }}
                                </span>
                            </div>

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