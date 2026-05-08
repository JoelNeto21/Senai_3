<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salvar Pokémon Local</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-hover: #1d4ed8;
            --danger-color: #ef4444;
            --danger-hover: #dc2626;
            --bg-body: #f4f7fb;
            --bg-card: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: #dbe3ef;
            --radius-lg: 20px;
            --radius-md: 14px;
            --radius-sm: 10px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            margin: 0;
            padding: 40px 24px 56px;
            background:
                radial-gradient(circle at top left, rgba(37, 99, 235, 0.12), transparent 34%),
                radial-gradient(circle at top right, rgba(14, 165, 233, 0.10), transparent 28%),
                var(--bg-body);
            color: var(--text-main);
            line-height: 1.5;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            display: grid;
            gap: 24px;
        }

        h1 {
            text-align: center;
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 8px 0;
            letter-spacing: -0.03em;
        }

        h2 {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0 0 20px 0;
            color: #0f172a;
        }

        .card {
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid rgba(148, 163, 184, 0.18);
            border-radius: var(--radius-lg);
            padding: 24px;
            box-shadow: 0 18px 50px rgba(15, 23, 42, 0.08);
            backdrop-filter: blur(10px);
        }

        .alert-success,
        .alert-error {
            padding: 12px 16px;
            border-radius: var(--radius-sm);
            margin-bottom: 20px;
            font-weight: 500;
        }

        .alert-success {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #065f46;
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        .alert-error ul {
            margin: 8px 0 0 0;
            padding-left: 20px;
        }

        .field {
            display: grid;
            gap: 8px;
            margin-bottom: 16px;
        }

        label {
            font-weight: 500;
            font-size: 0.95rem;
            color: #334155;
        }

        input[type="text"],
        input[type="file"] {
            padding: 12px 14px;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            width: 100%;
            background: #f8fafc;
            font-family: inherit;
            transition: all 0.2s ease;
        }

        input[type="text"]:focus,
        input[type="file"]:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
            background: #ffffff;
        }

        .primary-button,
        .modal-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: none;
            border-radius: var(--radius-sm);
            padding: 12px 20px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.15s ease, background-color 0.2s ease, box-shadow 0.2s ease;
        }

        .primary-button {
            background: var(--primary-color);
            color: #fff;
            width: 100%;
            margin-top: 8px;
            box-shadow: 0 10px 24px rgba(37, 99, 235, 0.22);
        }

        .primary-button:hover,
        .modal-button:hover {
            transform: translateY(-1px);
        }

        .primary-button:hover {
            background: var(--primary-hover);
        }

        .primary-button:active,
        .modal-button:active,
        .icon-button:active {
            transform: scale(0.98);
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
        }

        .pokemon-item {
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            overflow: hidden;
            background: var(--bg-card);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
            transition: transform 0.28s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.28s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: zoom-in;
        }

        .pokemon-item:hover {
            transform: translateY(-8px);
            box-shadow: 0 16px 30px rgba(15, 23, 42, 0.12);
        }

        .pokemon-item img {
            width: 100%;
            height: 220px;
            object-fit: contain;
            background: linear-gradient(180deg, #f8fafc 0%, #eef4ff 100%);
            display: block;
            border-bottom: 1px solid var(--border-color);
            padding: 10px;
        }

        .pokemon-item .content {
            padding: 16px;
        }

        .pokemon-item .content strong {
            display: block;
            font-size: 1.1rem;
            color: #0f172a;
            margin-bottom: 8px;
            text-align: center;
        }

        .meta-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .muted {
            color: var(--text-muted);
            font-size: 13px;
            font-weight: 500;
            white-space: nowrap;
        }

        .actions {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }

        .icon-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border-radius: 999px;
            border: 1px solid transparent;
            cursor: pointer;
            transition: transform 0.15s ease, background-color 0.2s ease, border-color 0.2s ease;
            color: #fff;
            flex: 0 0 auto;
        }

        .icon-button svg {
            width: 16px;
            height: 16px;
            fill: currentColor;
        }

        .icon-button.edit {
            background: #0f766e;
        }

        .icon-button.edit:hover {
            background: #115e59;
        }

        .icon-button.delete {
            background: var(--danger-color);
        }

        .icon-button.delete:hover {
            background: var(--danger-hover);
        }

        @media (min-width: 600px) {
            .primary-button {
                width: auto;
            }
        }

        .modal {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 1000;
            background-color: rgba(15, 23, 42, 0.84);
            backdrop-filter: blur(5px);
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .modal.active {
            display: flex;
        }

        .modal-dialog {
            width: min(100%, 640px);
            background: #ffffff;
            border-radius: 22px;
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.35);
            overflow: hidden;
            animation: zoomIn 0.22s ease;
        }

        .modal-dialog.modal-dialog-small {
            width: min(100%, 520px);
        }

        .modal-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            padding: 20px 22px 0;
        }

        .modal-header h3 {
            margin: 0;
            font-size: 1.2rem;
            color: #0f172a;
        }

        .modal-header p {
            margin: 6px 0 0;
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        .close-modal {
            border: none;
            background: #f1f5f9;
            color: #0f172a;
            width: 36px;
            height: 36px;
            border-radius: 999px;
            font-size: 24px;
            line-height: 1;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .close-modal:hover {
            background: #e2e8f0;
        }

        .modal-body {
            padding: 20px 22px 22px;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            padding-top: 10px;
            flex-wrap: wrap;
        }

        .modal-button.secondary {
            background: #e2e8f0;
            color: #0f172a;
        }

        .modal-button.secondary:hover {
            background: #cbd5e1;
        }

        .modal-button.danger {
            background: var(--danger-color);
            color: #fff;
        }

        .modal-button.danger:hover {
            background: var(--danger-hover);
        }

        .modal-preview {
            width: 100%;
            max-height: 320px;
            object-fit: contain;
            border-radius: 16px;
            background: linear-gradient(180deg, #f8fafc 0%, #eef4ff 100%);
            border: 1px solid var(--border-color);
            margin-bottom: 16px;
        }

        .modal-note {
            margin: 8px 0 0;
            color: var(--text-muted);
            font-size: 0.92rem;
        }

        .modal-warning {
            background: #fff7ed;
            border: 1px solid #fed7aa;
            color: #9a3412;
            border-radius: 14px;
            padding: 12px 14px;
            margin: 0 0 16px;
        }

        @keyframes zoomIn {
            from {
                transform: scale(0.95);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Salvar Pokémon (Imagem Local)</h1>

        <div class="card">
            @if(session('sucesso'))
                <div class="alert-success">{{ session('sucesso') }}</div>
            @endif

            @if($errors->any())
                <div class="alert-error">
                    <strong>Não foi possível salvar:</strong>
                    <ul>
                        @foreach($errors->all() as $erro)
                            <li>{{ $erro }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('pokemon-local.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="field">
                    <label for="nome">Nome do Pokémon:</label>
                    <input type="text" name="nome" id="nome" value="{{ old('nome') }}" required placeholder="Ex: Pikachu">
                </div>

                <div class="field">
                    <label for="imagem">Selecione a Imagem:</label>
                    <input type="file" name="imagem" id="imagem" accept="image/*" required>
                </div>

                <button type="submit" class="primary-button">Salvar no Banco</button>
            </form>
        </div>

        <div class="card">
            <h2>Pokémons salvos localmente</h2>
            @if(($pokemonLocais ?? collect())->isEmpty())
                <p class="muted">Nenhum Pokémon salvo ainda.</p>
            @else
                <div class="grid">
                    @foreach($pokemonLocais->sortBy('id') as $pokemonLocal)
                        <article class="pokemon-item" onclick="openImageModal(@js(asset('storage/' . $pokemonLocal->caminho_imagem)))">
                            <img src="{{ asset('storage/' . $pokemonLocal->caminho_imagem) }}" alt="{{ $pokemonLocal->nome }}">
                            <div class="content">
                                <strong>{{ $pokemonLocal->nome }}</strong>
                                <div class="meta-row">
                                    <span class="muted">ID: #{{ str_pad($pokemonLocal->id, 3, '0', STR_PAD_LEFT) }}</span>
                                    <div class="actions">
                                        <button
                                            type="button"
                                            class="icon-button edit"
                                            title="Editar Pokémon"
                                            aria-label="Editar Pokémon"
                                            data-pokemon-id="{{ $pokemonLocal->id }}"
                                            onclick="event.stopPropagation(); openEditModal(Number(this.dataset.pokemonId))"
                                        >
                                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                                <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zm2.92 2.83H5v-.92l9.06-9.06.92.92-9.06 9.06zM20.71 7.04a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                                            </svg>
                                        </button>
                                        <button
                                            type="button"
                                            class="icon-button delete"
                                            title="Excluir Pokémon"
                                            aria-label="Excluir Pokémon"
                                            data-pokemon-id="{{ $pokemonLocal->id }}"
                                            onclick="event.stopPropagation(); openDeleteModal(Number(this.dataset.pokemonId))"
                                        >
                                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                                <path d="M6 7h12l-1 13H7L6 7zm3-3h6l1 2H8l1-2zm-4 2h14v2H5V6zm4 4v8h2v-8H9zm4 0v8h2v-8h-2z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div id="imageModal" class="modal" aria-hidden="true">
        <button type="button" class="close-modal" onclick="closeImageModal()" aria-label="Fechar visualização">&times;</button>
        <img class="modal-content" id="imgModalSrc" alt="Imagem ampliada do Pokémon">
    </div>

    <div id="editModal" class="modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-header">
                <div>
                    <h3>Editar Pokémon</h3>
                    <p id="editPokemonSubtitle">Altere o nome e, se quiser, substitua a imagem.</p>
                </div>
                <button type="button" class="close-modal" onclick="closeEditModal()" aria-label="Fechar edição">&times;</button>
            </div>
            <div class="modal-body">
                <img id="editCurrentImage" class="modal-preview" alt="Pré-visualização atual do Pokémon">

                <form id="editPokemonForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="field">
                        <label for="editNome">Nome do Pokémon:</label>
                        <input type="text" name="nome" id="editNome" required>
                    </div>

                    <div class="field">
                        <label for="editImagem">Nova imagem</label>
                        <input type="file" name="imagem" id="editImagem" accept="image/*">
                        <p class="modal-note">Deixe em branco para manter a imagem atual.</p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="modal-button secondary" onclick="closeEditModal()">Cancelar</button>
                        <button type="submit" class="modal-button">Salvar alterações</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="deleteModal" class="modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-small">
            <div class="modal-header">
                <div>
                    <h3>Confirmar exclusão</h3>
                    <p>Essa ação remove o registro e a imagem armazenada.</p>
                </div>
                <button type="button" class="close-modal" onclick="closeDeleteModal()" aria-label="Fechar confirmação">&times;</button>
            </div>
            <div class="modal-body">
                <div class="modal-warning" id="deletePokemonInfo">
                    Tem certeza que deseja excluir este Pokémon?
                </div>

                <form id="deletePokemonForm" method="POST">
                    @csrf
                    @method('DELETE')

                    <div class="modal-footer">
                        <button type="button" class="modal-button secondary" onclick="closeDeleteModal()">Cancelar</button>
                        <button type="submit" class="modal-button danger">Excluir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const pokemonLocais = @js($pokemonLocais->map(function ($pokemonLocal) {
            return [
                'id' => $pokemonLocal->id,
                'nome' => $pokemonLocal->nome,
                'imagem' => asset('storage/' . $pokemonLocal->caminho_imagem),
                'updateUrl' => route('pokemon-local.update', $pokemonLocal),
                'deleteUrl' => route('pokemon-local.destroy', $pokemonLocal),
            ];
        })->values());

        const initialEditPokemonId = @js(session('openEditPokemonId'));
        const initialEditNome = @js(old('nome'));

        const imageModal = document.getElementById('imageModal');
        const imageModalImg = document.getElementById('imgModalSrc');
        const editModal = document.getElementById('editModal');
        const editForm = document.getElementById('editPokemonForm');
        const editTitle = document.getElementById('editPokemonSubtitle');
        const editNameInput = document.getElementById('editNome');
        const editImageInput = document.getElementById('editImagem');
        const editCurrentImage = document.getElementById('editCurrentImage');
        const deleteModal = document.getElementById('deleteModal');
        const deleteForm = document.getElementById('deletePokemonForm');
        const deleteInfo = document.getElementById('deletePokemonInfo');

        function updateBodyLock() {
            const anyModalOpen = [imageModal, editModal, deleteModal].some((element) => element.classList.contains('active'));
            document.body.style.overflow = anyModalOpen ? 'hidden' : 'auto';
        }

        function findPokemonLocal(pokemonId) {
            return pokemonLocais.find((item) => String(item.id) === String(pokemonId));
        }

        function openImageModal(imageSrc) {
            imageModalImg.src = imageSrc;
            imageModal.classList.add('active');
            imageModal.setAttribute('aria-hidden', 'false');
            updateBodyLock();
        }

        function closeImageModal() {
            imageModal.classList.remove('active');
            imageModal.setAttribute('aria-hidden', 'true');
            imageModalImg.src = '';
            updateBodyLock();
        }

        function openEditModal(pokemonId) {
            const pokemon = findPokemonLocal(pokemonId);

            if (!pokemon) {
                return;
            }

            editForm.action = pokemon.updateUrl;
            editTitle.textContent = `Editando ${pokemon.nome}`;
            editNameInput.value = pokemon.nome;
            editImageInput.value = '';
            editCurrentImage.src = pokemon.imagem;
            editCurrentImage.alt = `Imagem atual de ${pokemon.nome}`;
            editModal.classList.add('active');
            editModal.setAttribute('aria-hidden', 'false');
            updateBodyLock();
        }

        function closeEditModal() {
            editModal.classList.remove('active');
            editModal.setAttribute('aria-hidden', 'true');
            editForm.action = '';
            updateBodyLock();
        }

        function openDeleteModal(pokemonId) {
            const pokemon = findPokemonLocal(pokemonId);

            if (!pokemon) {
                return;
            }

            deleteForm.action = pokemon.deleteUrl;
            deleteInfo.textContent = `Tem certeza que deseja excluir ${pokemon.nome}? Essa ação remove também a imagem salva.`;
            deleteModal.classList.add('active');
            deleteModal.setAttribute('aria-hidden', 'false');
            updateBodyLock();
        }

        function closeDeleteModal() {
            deleteModal.classList.remove('active');
            deleteModal.setAttribute('aria-hidden', 'true');
            deleteForm.action = '';
            updateBodyLock();
        }

        document.getElementById('imagem').addEventListener('change', function (event) {
            if (event.target.files.length > 0) {
                const arquivo = event.target.files[0];
                const nomeArquivo = arquivo.name;
                const nomeSemExtensao = nomeArquivo.replace(/\.[^/.]+$/, '');
                const nomeFormatado = nomeSemExtensao.charAt(0).toUpperCase() + nomeSemExtensao.slice(1);
                const campoNome = document.getElementById('nome');

                if (!campoNome.value || campoNome.value.trim() === '') {
                    campoNome.value = nomeFormatado;
                }
            }
        });

        editImageInput.addEventListener('change', function (event) {
            if (event.target.files.length > 0) {
                editCurrentImage.src = URL.createObjectURL(event.target.files[0]);
            }
        });

        [imageModal, editModal, deleteModal].forEach((modalElement) => {
            modalElement.addEventListener('click', function (event) {
                if (event.target === modalElement) {
                    if (modalElement === imageModal) {
                        closeImageModal();
                    }

                    if (modalElement === editModal) {
                        closeEditModal();
                    }

                    if (modalElement === deleteModal) {
                        closeDeleteModal();
                    }
                }
            });
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeImageModal();
                closeEditModal();
                closeDeleteModal();
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            if (initialEditPokemonId) {
                const pokemon = findPokemonLocal(initialEditPokemonId);

                if (pokemon) {
                    openEditModal(pokemon.id);

                    if (initialEditNome) {
                        editNameInput.value = initialEditNome;
                    }
                }
            }
        });
    </script>
</body>
</html>