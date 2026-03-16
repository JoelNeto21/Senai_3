<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Fornecedor: {{ $supplier->nome }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="p-8">
                    <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="col-span-full">
                                <label for="nome" class="block text-sm font-bold text-gray-700 mb-1">Nome / Razão Social</label>
                                <input type="text" name="nome" id="nome_format" value="{{ old('nome', $supplier->nome) }}" 
                                    class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm" 
                                    maxlength="100" required>
                                @error('nome') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="cnpj" class="block text-sm font-bold text-gray-700 mb-1">CNPJ</label>
                                <input type="text" name="cnpj" id="cnpj_mask" value="{{ old('cnpj', $supplier->cnpj) }}" 
                                    class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm" required>
                                @error('cnpj') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="telefone" class="block text-sm font-bold text-gray-700 mb-1">Telefone</label>
                                <input type="text" name="telefone" id="phone_mask" value="{{ old('telefone', $supplier->telefone) }}" 
                                    class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm" required>
                                @error('telefone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="col-span-full">
                                <label for="categoria" class="block text-sm font-bold text-gray-700 mb-1">Categoria</label>
                                <select name="categoria" id="categoria" 
                                    class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm" required>
                                    @foreach(['Tecidos', 'Linhas e Fios', 'Estamparia', 'Tingimento', 'Produtos Químicos'] as $cat)
                                        <option value="{{ $cat }}" {{ old('categoria', $supplier->categoria) == $cat ? 'selected' : '' }}>
                                            {{ $cat }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('categoria') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-100">
                            <a href="{{ route('suppliers.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
                                Cancelar
                            </a>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                                Atualizar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/imask"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Máscara de CNPJ
            IMask(document.getElementById('cnpj_mask'), { mask: '00.000.000/0000-00' });
            
            // Máscara de Telefone
            IMask(document.getElementById('phone_mask'), {
                mask: [{ mask: '(00) 0000-0000' }, { mask: '(00) 00000-0000' }]
            });

            // Formatação do Nome/Razão Social
            const inputNome = document.getElementById('nome_format');
            
            inputNome.addEventListener('input', function(e) {
                let cursorPosition = this.selectionStart;
                let val = this.value;

                // Permite letras, espaços, alguns caracteres especiais para Razão Social
                val = val.replace(/[^a-zA-ZÀ-ÿ\s&\-.]/g, "");
                val = val.replace(/\s{2,}/g, " ");
                val = val.replace(/^\s/g, "");

                const exceptions = ['de', 'di', 'do', 'da', 'dos', 'das', 'e', 'ltda', 'sa', 'me', 'epp'];
                const romanNumerals = /^M{0,4}(CM|CD|D?C{0,3})(XC|XL|L?X{0,3})(IX|IV|V?I{0,3})$/i;

                let words = val.toLowerCase().split(' ');

                let formatted = words.map((word, index) => {
                    if (word === "") return "";
                    
                    if (word.length > 1 && romanNumerals.test(word.toUpperCase())) {
                        return word.toUpperCase();
                    }

                    if (['ltda', 'sa', 'me', 'epp'].includes(word)) return word.toUpperCase();
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
        });
    </script>
</x-app-layout>