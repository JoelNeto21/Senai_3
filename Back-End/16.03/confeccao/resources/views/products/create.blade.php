<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Novo Produto no Catálogo
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="p-8">
                    <form action="{{ route('products.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div class="col-span-full">
                                <label for="nome" class="block text-sm font-bold text-gray-700 mb-1">Nome do Produto / Modelo</label>
                                <input type="text" name="nome" id="nome_format" value="{{ old('nome') }}" 
                                    class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm" 
                                    placeholder="Ex: Camiseta Polo Algodão Premium" required>
                                @error('nome') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="quantidade" class="block text-sm font-bold text-gray-700 mb-1">Quantidade em Estoque</label>
                                <div class="relative">
                                    <input type="text" inputmode="numeric" name="quantidade" id="quantidade" value="{{ old('quantidade', 0) }}" 
                                        class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm pl-10" 
                                        required>
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                    </div>
                                </div>
                                @error('quantidade') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="valor" class="block text-sm font-bold text-gray-700 mb-1">Valor de Venda (Unitário)</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">R$</span>
                                    </div>
                                    <input type="text" inputmode="numeric" name="valor" id="valor" value="{{ old('valor') }}" 
                                        class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm pl-10" 
                                        placeholder="0,00" required>
                                </div>
                                @error('valor') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="col-span-full">
                                <label for="descricao" class="block text-sm font-bold text-gray-700 mb-1">Descrição Técnica</label>
                                <textarea name="descricao" id="descricao" rows="4" 
                                    class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm" 
                                    placeholder="Composição do tecido, numerações disponíveis, especificações de lavagem..."
                                    required>{{ old('descricao') }}</textarea>
                                @error('descricao') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-100">
                            <a href="{{ route('products.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
                                Cancelar
                            </a>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                                Registrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // 1. Máscara de Formatação do Nome
            const inputNome = document.getElementById('nome_format');
            if (inputNome) {
                inputNome.addEventListener('input', function(e) {
                    let cursorPosition = this.selectionStart;
                    let val = this.value;

                    // Remove caracteres estranhos, mantém letras, números e hífens
                    val = val.replace(/[^a-zA-ZÀ-ÿ0-9\s\-]/g, ""); 
                    val = val.replace(/\s{2,}/g, " ");
                    val = val.replace(/^\s/g, "");

                    const exceptions = ['de', 'di', 'do', 'da', 'dos', 'das', 'e', 'com', 'sem'];
                    const romanNumerals = /^M{0,4}(CM|CD|D?C{0,3})(XC|XL|L?X{0,3})(IX|IV|V?I{0,3})$/i;

                    let words = val.toLowerCase().split(' ');

                    let formatted = words.map((word, index) => {
                        if (word === "") return "";
                        if (index === words.length - 1 && word.length < 3) return word;
                        if (word.length > 1 && romanNumerals.test(word.toUpperCase())) return word.toUpperCase();
                        if (exceptions.includes(word)) return word;
                        return word.charAt(0).toUpperCase() + word.slice(1);
                    }).join(' ');

                    if (this.value !== formatted) {
                        this.value = formatted;
                        this.setSelectionRange(cursorPosition, cursorPosition);
                    }
                });

                inputNome.addEventListener('blur', function() {
                    this.value = this.value.trim();
                });
            }

            // 2. Máscara de Quantidade (Apenas Números Inteiros)
            const inputQtd = document.getElementById('quantidade');
            if (inputQtd) {
                inputQtd.addEventListener('input', function(e) {
                    this.value = this.value.replace(/\D/g, ''); // Remove tudo que não for dígito
                });
            }

            // 3. Máscara de Valor (Moeda BRL - Decimal)
            const inputValor = document.getElementById('valor');
            if (inputValor) {
                inputValor.addEventListener('input', function(e) {
                    let value = this.value.replace(/\D/g, ""); // Remove tudo que não for dígito
                    
                    if (value === "") {
                        this.value = "";
                        return;
                    }

                    // Divide por 100 para criar as casas decimais e converte para string
                    value = (parseInt(value, 10) / 100).toFixed(2) + "";
                    
                    // Troca ponto por vírgula
                    value = value.replace(".", ",");
                    
                    // Adiciona o ponto de milhar
                    value = value.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
                    
                    this.value = value;
                });
            }
        });
    </script>
</x-app-layout>