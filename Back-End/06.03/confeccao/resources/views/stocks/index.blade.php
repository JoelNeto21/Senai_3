<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Controle de Estoque</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($stocks as $stock)
                    <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:shadow-md transition-all duration-200 relative overflow-hidden flex flex-col justify-between">
                        
                        <div class="flex justify-between items-start">
                            <div class="flex items-center gap-2">
                                @if($stock->quantidade < 10)
                                    <span class="flex h-2 w-2">
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                                    </span>
                                @endif
                            </div>

                            <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">
                                {{ $stock->categoria }}
                            </span>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-black text-gray-900 uppercase truncate" title="{{ $stock->item }}">
                                {{ $stock->item }}
                            </h3>
                            <p class="text-xs text-gray-500 line-clamp-2 h-8 leading-relaxed mt-1">
                                {{ $stock->descricao }}
                            </p>
                        </div>

                        <div class="flex items-center pt-4 border-t border-gray-100 mt-auto">
                            <div class="flex items-center gap-2 text-sm ml-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-700 shrink-0" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12.378 1.602a.75.75 0 0 0-.756 0L3 6.632l9 5.25 9-5.25-8.622-5.03ZM21.75 7.93l-9 5.25v9l8.628-5.032a.75.75 0 0 0 .372-.648V7.93ZM11.25 22.18v-9l-9-5.25v8.57a.75.75 0 0 0 .372.648l8.628 5.033Z" />
                                </svg>

                                <span class="leading-none flex items-center">
                                    <b class="font-black text-gray-900 text-[10px] tracking-wider">Qtd.:</b> 
                                    <span class="text-sm font-mono font-black ml-1 {{ $stock->quantidade < 10 ? 'text-red-600' : 'text-green-600' }}">
                                        {{ str_pad($stock->quantidade, 2, '0', STR_PAD_LEFT) }}
                                    </span>
                                </span>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>