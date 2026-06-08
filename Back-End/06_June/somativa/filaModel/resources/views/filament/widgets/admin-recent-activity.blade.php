<x-filament-widgets::widget>
    <x-filament::section
        heading="Atividades recentes e alertas"
        description="Últimas movimentações, pedidos pendentes e produtos que exigem atenção."
    >
        <div class="grid gap-6 lg:grid-cols-3">
            <div>
                <h3 class="text-sm font-semibold text-gray-950 dark:text-white">Últimas movimentações</h3>
                <div class="mt-3 space-y-3">
                    @forelse ($movimentacoes as $movimentacao)
                        <div class="rounded-lg border border-gray-200 p-3 text-sm dark:border-white/10">
                            <div class="font-medium">{{ $movimentacao->produto?->nome ?? 'Produto removido' }}</div>
                            <div class="text-gray-600 dark:text-gray-400">
                                {{ $movimentacao->movimentacao }} de {{ $movimentacao->quantidade }} un.
                            </div>
                            <div class="text-xs text-gray-500">{{ $movimentacao->created_at?->format('d/m/Y H:i') }}</div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-600 dark:text-gray-400">Nenhuma movimentação registrada.</p>
                    @endforelse
                </div>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-gray-950 dark:text-white">Pedidos pendentes</h3>
                <div class="mt-3 space-y-3">
                    @forelse ($pedidosPendentes as $pedido)
                        <div class="rounded-lg border border-gray-200 p-3 text-sm dark:border-white/10">
                            <div class="font-medium">Pedido #{{ $pedido->id }}</div>
                            <div class="text-gray-600 dark:text-gray-400">{{ $pedido->cliente?->nome ?? 'Cliente removido' }}</div>
                            <div class="text-xs text-gray-500">{{ $pedido->created_at?->format('d/m/Y H:i') }}</div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-600 dark:text-gray-400">Não há pedidos pendentes.</p>
                    @endforelse
                </div>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-gray-950 dark:text-white">Estoque baixo</h3>
                <div class="mt-3 space-y-3">
                    @forelse ($estoqueBaixo as $produto)
                        <div class="rounded-lg border border-danger-200 bg-danger-50 p-3 text-sm dark:border-danger-500/30 dark:bg-danger-500/10">
                            <div class="font-medium">{{ $produto->nome }}</div>
                            <div class="text-danger-700 dark:text-danger-300">{{ $produto->quantidade }} unidades restantes</div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-600 dark:text-gray-400">Nenhum produto com estoque baixo.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
