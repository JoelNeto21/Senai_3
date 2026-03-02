<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Controle de Estoque</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($stocks as $stock)
                    <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:shadow-md transition-all duration-200 relative overflow-hidden">
                        @if($stock->quantidade < 10)
                            <div class="absolute top-0 right-0 mt-2 mr-2">
                                <span class="flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                                </span>
                            </div>
                        @endif

                        <div class="mb-4">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400 block mb-1">{{ $stock->categoria }}</span>
                            <h3 class="text-lg font-black text-gray-900 uppercase truncate" title="{{ $stock->item }}">{{ $stock->item }}</h3>
                        </div>

                        <p class="text-xs text-gray-500 mb-6 line-clamp-2 h-8 leading-relaxed">{{ $stock->descricao }}</p>

                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <div>
                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">Saldo em Unidades</p>
                                <p class="text-3xl font-mono font-bold {{ $stock->quantidade < 10 ? 'text-red-600' : 'text-slate-800' }}">
                                    {{ str_pad($stock->quantidade, 2, '0', STR_PAD_LEFT) }}
                                </p>
                            </div>
                            <!-- <button class="h-10 w-10 flex items-center justify-center bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors shadow-lg shadow-gray-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            </button> -->
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>