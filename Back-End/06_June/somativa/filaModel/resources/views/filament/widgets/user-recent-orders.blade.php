<x-filament-widgets::widget>
    <x-filament::section
        heading="Meus pedidos recentes"
        description="Histórico resumido vinculado ao e-mail da sua conta."
    >
        <div class="space-y-3">
            @forelse ($pedidos as $pedido)
                <div class="flex flex-col gap-2 rounded-lg border border-gray-200 p-4 text-sm dark:border-white/10 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <div class="font-medium">Pedido #{{ $pedido->id }}</div>
                        <div class="text-gray-600 dark:text-gray-400">{{ $pedido->created_at?->format('d/m/Y H:i') }}</div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700 dark:bg-white/10 dark:text-gray-200">
                            {{ $pedido->status }}
                        </span>
                        <span class="font-semibold">{{ \App\Support\BrazilianFormat::currency($pedido->valor_total) }}</span>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-600 dark:text-gray-400">Nenhum pedido vinculado ao seu e-mail até o momento.</p>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
