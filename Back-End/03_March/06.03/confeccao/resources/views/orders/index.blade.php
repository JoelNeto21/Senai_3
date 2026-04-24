<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gerenciamento de Pedidos
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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