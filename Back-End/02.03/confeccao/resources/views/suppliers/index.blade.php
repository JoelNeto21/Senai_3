<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Nossa Confecção</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($suppliers as $supplier)
                    <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:shadow-md transition-all duration-200">
                        <div class="flex justify-end mb-0">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">
                                {{ $supplier->categoria }}
                            </span>
                        </div>
                        
                        <div class="flex items-center gap-2 mb-6">
                            <div style="width: 48px; height: 48px; flex-shrink: 0; display: flex; align-items: center; justify-content: center;">
                                <svg 
                                    xmlns="http://www.w3.org/2000/svg" 
                                    viewBox="0 0 24 24" 
                                    fill="none" 
                                    stroke="currentColor" 
                                    stroke-width="1.5" 
                                    style="width: 90%; height: 90%; color: #111827;"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z" />
                                </svg>
                            </div>

                            <div class="min-w-0">
                                <h3 class="text-lg font-bold text-blue-600 leading-tight truncate">
                                    {{ $supplier->nome }}
                                </h3>
                                <p class="text-sm text-gray-500">
                                    CNPJ: {{ $supplier->cnpj }}
                                </p>
                            </div>
                        </div>
                                                                        
                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <div class="flex items-center gap-2 text-sm text-gray-700 ml-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black shrink-0" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M1.5 4.5a3 3 0 013-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 01-.694 1.912l-1.563 1.172a15.023 15.023 0 006.22 6.22l1.172-1.563a1.875 1.875 0 011.912-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 01-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5z" />
                                </svg>
                                
                                <span class="leading-none">
                                    <b class="font-black text-gray-900">Tel.:</b> 
                                    <a href="tel:{{ $supplier->telefone }}" class="font-bold text-blue-600 hover:underline">{{ $supplier->telefone }}</a> 
                                    <span class="text-gray-400 text-xs ml-1">(Administrativo)</span>
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full flex flex-col items-center justify-center py-12 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
                        <p class="text-gray-500 font-medium">Nenhum fornecedor encontrado no sistema da confecção.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>