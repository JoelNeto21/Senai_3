<div class="rounded-xl bg-primary-50 p-4 dark:bg-primary-900/50 mb-6 border border-primary-200 dark:border-primary-800">
    <div class="flex items-center gap-3">
        <svg class="h-6 w-6 text-primary-600 dark:text-primary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
        </svg>

        <div>
            <h3 class="font-semibold text-primary-800 dark:text-primary-300">
                Dica de Cadastro de Fornecedor
            </h3>
            
            <p class="text-sm text-primary-700 dark:text-primary-400 mt-1">
                @if ($record)
                    Você está editando o fornecedor <strong>{{ $record->nome }}</strong>. Verifique se o CNPJ ainda está ativo na Receita Federal.
                @else
                    Atenção ao cadastrar um novo fornecedor! Preencha o CNPJ corretamente para evitar problemas de faturamento no futuro.
                @endif
            </p>
        </div>
    </div>
</div>