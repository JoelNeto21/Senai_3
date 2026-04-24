<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Controle de Estoque
            </h2>
            <a href="{{ route('stocks.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 transition ease-in-out duration-150">
                + Novo Item
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