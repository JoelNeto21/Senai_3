<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Cadastrar Novo Cliente
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="p-8">
                    <form action="{{ route('clients.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="col-span-full">
                                <label for="nome" class="block text-sm font-bold text-gray-700 mb-1">Nome Completo</label>
                                <input type="text" name="nome" id="nome_format" value="{{ old('nome') }}" 
                                    class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm" 
                                    placeholder="Ex: João da Silva" maxlength="100" required>
                                @error('nome') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="cpf" class="block text-sm font-bold text-gray-700 mb-1">CPF</label>
                                <input type="text" name="cpf" id="cpf_mask" value="{{ old('cpf') }}" 
                                    class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm" 
                                    placeholder="000.000.000-00" required>
                                @error('cpf') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="telefone" class="block text-sm font-bold text-gray-700 mb-1">Telefone</label>
                                <input type="text" name="telefone" id="phone_mask" value="{{ old('telefone') }}" 
                                    class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm" 
                                    placeholder="(00) 00000-0000" required>
                                @error('telefone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="col-span-full">
                                <label for="reserva" class="block text-sm font-bold text-gray-700 mb-1">Reserva (Opcional)</label>
                                <div class="relative">
                                    <input type="number" name="reserva" id="reserva" value="{{ old('reserva') }}" 
                                        class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm pl-10" 
                                        placeholder="Valor ou número da reserva" oninput="if(this.value.length > 10) this.value = this.value.slice(0, 10);">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-400 text-sm">#</span>
                                    </div>
                                </div>
                                @error('reserva') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-100">
                            <a href="{{ route('clients.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
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

    <script src="https://unpkg.com/imask"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            IMask(document.getElementById('cpf_mask'), { mask: '000.000.000-00' });
            IMask(document.getElementById('phone_mask'), {
                mask: [{ mask: '(00) 0000-0000' }, { mask: '(00) 00000-0000' }]
            });

            const inputNome = document.getElementById('nome_format');
            
            inputNome.addEventListener('input', function(e) {
                let cursorPosition = this.selectionStart;
                let val = this.value;


                val = val.replace(/[^a-zA-ZÀ-ÿ\s]/g, "");

                val = val.replace(/\s{2,}/g, " ");
                val = val.replace(/^\s/g, "");

                const exceptions = ['de', 'di', 'do', 'da', 'dos', 'das', 'e'];
                const romanNumerals = /^M{0,4}(CM|CD|D?C{0,3})(XC|XL|L?X{0,3})(IX|IV|V?I{0,3})$/i;

                let words = val.toLowerCase().split(' ');

                let formatted = words.map((word, index) => {
                    if (word === "") return "";
                    
                    if (index === words.length - 1 && word.length < 3) return word;

                    if (word.length > 1 && romanNumerals.test(word.toUpperCase())) {
                        return word.toUpperCase();
                    }

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