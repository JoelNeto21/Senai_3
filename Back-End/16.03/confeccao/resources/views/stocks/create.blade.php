<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Entrada de Material no Estoque
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="p-8">
                    <form action="{{ route('stocks.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div class="col-span-full">
                                <label for="item" class="block text-sm font-bold text-gray-700 mb-1">Nome do Item / Insumo</label>
                                <input type="text" name="item" id="item" value="{{ old('item') }}" 
                                    class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm" 
                                    placeholder="Ex: Tecido Algodão Cru, Linha de Costura 40..." required>
                                @error('item') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="categoria" class="block text-sm font-bold text-gray-700 mb-1">Categoria</label>
                                <select name="categoria" id="categoria" 
                                    class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm" required>
                                    <option value="" disabled selected>Selecione uma categoria</option>
                                    <option value="Tecidos" {{ old('categoria') == 'Tecidos' ? 'selected' : '' }}>Tecidos</option>
                                    <option value="Aviamentos" {{ old('categoria') == 'Aviamentos' ? 'selected' : '' }}>Aviamentos (Botões, Zíperes)</option>
                                    <option value="Linhas" {{ old('categoria') == 'Linhas' ? 'selected' : '' }}>Linhas e Fios</option>
                                    <option value="Embalagens" {{ old('categoria') == 'Embalagens' ? 'selected' : '' }}>Embalagens</option>
                                    <option value="Outros" {{ old('categoria') == 'Outros' ? 'selected' : '' }}>Outros</option>
                                </select>
                                @error('categoria') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="quantidade" class="block text-sm font-bold text-gray-700 mb-1">Quantidade Atual</label>
                                <div class="relative">
                                    <input type="number" name="quantidade" id="quantidade" value="{{ old('quantidade', 0) }}" 
                                        class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm pl-10" 
                                        min="0" required>
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                    </div>
                                </div>
                                @error('quantidade') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="col-span-full">
                                <label for="descricao" class="block text-sm font-bold text-gray-700 mb-1">Descrição / Notas de Armazenamento</label>
                                <textarea name="descricao" id="descricao" rows="3" maxlength="500"
                                    class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm" 
                                    placeholder="Ex: Prateleira B4, Fornecedor X, Unidade de medida (metros/unidades)..."
                                    required>{{ old('descricao') }}</textarea>
                                @error('descricao') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-100">
                            <a href="{{ route('stocks.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
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
</x-app-layout>