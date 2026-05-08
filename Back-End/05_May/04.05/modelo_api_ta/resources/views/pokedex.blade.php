<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokédex Grid | {{ $selectedType ? ucfirst($selectedType) : 'Todos os tipos' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@400;500;600;700&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <style>
        :root {
            --accent-color: {{ $accentColor ?? '#7AC74C' }};
            --accent-color-rgb: {{ $accentColorRgb ?? '122, 199, 76' }};
            --accent-glow: rgba(var(--accent-color-rgb), 0.55);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Rajdhani', sans-serif;
            background:
                radial-gradient(circle at top, rgba(var(--accent-color-rgb), 0.15), transparent 40%),
                linear-gradient(180deg, #071018 0%, #030509 60%, #010204 100%);
            color: #f6f7fb;
            overflow-x: hidden;
        }

        .bg-grid {
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(var(--accent-color-rgb), 0.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(var(--accent-color-rgb), 0.06) 1px, transparent 1px);
            background-size: 42px 42px;
            pointer-events: none;
            z-index: 0;
        }

        .bg-scan {
            position: fixed;
            inset: 0;
            background: repeating-linear-gradient(
                180deg,
                transparent,
                transparent 3px,
                rgba(255, 255, 255, 0.025) 3px,
                rgba(255, 255, 255, 0.025) 6px
            );
            pointer-events: none;
            z-index: 0;
        }

        .bg-vignette {
            position: fixed;
            inset: 0;
            background: radial-gradient(ellipse at center, transparent 35%, rgba(0, 0, 0, 0.75) 100%);
            pointer-events: none;
            z-index: 0;
        }

        @keyframes float-up {
            0% { transform: translateY(100vh) translateX(0); opacity: 0; }
            12% { opacity: 1; }
            100% { transform: translateY(-20vh) translateX(24px); opacity: 0; }
        }

        .particle {
            position: fixed;
            width: 2px;
            height: 2px;
            border-radius: 999px;
            background: var(--accent-color);
            animation: float-up linear infinite;
            pointer-events: none;
            z-index: 0;
        }

        .page-shell {
            position: relative;
            z-index: 1;
        }

        .page-title {
            font-family: 'Orbitron', monospace;
            letter-spacing: 0.22em;
            text-transform: uppercase;
        }

        .page-subtitle,
        .status-line,
        .filter-label,
        .quick-chip,
        .type-chip,
        .meta-chip,
        .dex-id,
        .dex-caption {
            font-family: 'Share Tech Mono', monospace;
        }

        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(var(--accent-color-rgb), 0.9), transparent);
        }

        .ui-panel {
            background: rgba(6, 10, 16, 0.74);
            border: 1px solid rgba(var(--accent-color-rgb), 0.24);
            backdrop-filter: blur(18px);
            box-shadow: 0 0 0 1px rgba(255, 255, 255, 0.02) inset;
        }

        .search-terminal {
            position: relative;
            border-radius: 14px;
            transition: border-color 0.25s, box-shadow 0.25s;
        }

        .search-terminal:focus-within {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 1px rgba(var(--accent-color-rgb), 0.38), 0 0 26px rgba(var(--accent-color-rgb), 0.16);
        }

        .search-terminal::before {
            content: '> POKÉDEX_SCAN://';
            position: absolute;
            top: -10px;
            left: 14px;
            padding: 0 8px;
            background: #030509;
            color: var(--accent-color);
            letter-spacing: 0.12em;
            font-size: 10px;
        }

        .search-input,
        .filter-select {
            width: 100%;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            color: #f8fafc;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .search-input {
            padding: 0.95rem 1rem;
            font-family: 'Share Tech Mono', monospace;
        }

        .search-input::placeholder {
            color: rgba(255, 255, 255, 0.22);
        }

        .filter-select {
            padding: 0.75rem 0.9rem;
            font-family: 'Rajdhani', sans-serif;
        }

        .search-input:focus,
        .filter-select:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 1px rgba(var(--accent-color-rgb), 0.35);
        }

        .search-btn,
        .ghost-btn {
            border: none;
            border-radius: 12px;
            padding: 0.9rem 1.15rem;
            font-family: 'Orbitron', monospace;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            transition: transform 0.2s, filter 0.2s, background 0.2s;
        }

        .search-btn {
            background: var(--accent-color);
            color: #030509;
        }

        .ghost-btn {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: rgba(255, 255, 255, 0.78);
        }

        .search-btn:hover,
        .ghost-btn:hover,
        .quick-chip:hover {
            transform: translateY(-1px);
            filter: brightness(1.08);
        }

        .quick-chip {
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 999px;
            padding: 0.4rem 0.8rem;
            font-size: 11px;
            color: rgba(255, 255, 255, 0.72);
            background: rgba(255, 255, 255, 0.03);
        }

        .quick-chip.is-active {
            background: rgba(var(--accent-color-rgb), 0.12);
            border-color: rgba(var(--accent-color-rgb), 0.45);
            color: var(--accent-color);
        }

        .dex-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
        }

        .dex-card {
            position: relative;
            overflow: hidden;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background:
                radial-gradient(circle at top, rgba(var(--pokemon-accent-rgb), 0.14), transparent 42%),
                linear-gradient(180deg, rgba(12, 18, 26, 0.94), rgba(5, 8, 12, 0.98));
            min-height: 320px;
            box-shadow: 0 18px 42px rgba(0, 0, 0, 0.32);
            transition: transform 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
            cursor: pointer;
        }

        .dex-card:hover {
            transform: translateY(-4px);
            border-color: rgba(var(--pokemon-accent-rgb), 0.4);
            box-shadow: 0 20px 46px rgba(0, 0, 0, 0.44), 0 0 28px rgba(var(--pokemon-accent-rgb), 0.1);
        }

        .dex-card::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, transparent 30%, rgba(255, 255, 255, 0.03) 50%, transparent 70%);
            pointer-events: none;
        }

        .dex-card-topline {
            height: 3px;
            background: linear-gradient(90deg, transparent, var(--pokemon-accent), transparent);
        }

        .dex-art {
            background: radial-gradient(circle at top, rgba(var(--pokemon-accent-rgb), 0.18), transparent 58%);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 18px;
        }

        .dex-art img {
            width: 100%;
            height: 168px;
            object-fit: contain;
            filter: drop-shadow(0 16px 22px rgba(0, 0, 0, 0.38));
        }

        .dex-name {
            font-family: 'Orbitron', monospace;
            font-size: 1rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .dex-id {
            font-size: 11px;
            letter-spacing: 0.18em;
            color: var(--pokemon-accent);
        }

        .type-chip,
        .meta-chip {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            padding: 0.28rem 0.6rem;
            font-size: 11px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            border: 1px solid rgba(var(--pokemon-accent-rgb), 0.32);
            color: var(--pokemon-accent);
            background: rgba(var(--pokemon-accent-rgb), 0.08);
        }

        .empty-state {
            border-radius: 22px;
            border: 1px dashed rgba(var(--accent-color-rgb), 0.35);
            background: rgba(255, 255, 255, 0.03);
        }

        .status-bar {
            color: rgba(255, 255, 255, 0.42);
            letter-spacing: 0.16em;
        }

        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(1, 3, 6, 0.78);
            backdrop-filter: blur(14px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 50;
            padding: 1rem;
        }

        .modal-overlay.is-open {
            display: flex;
        }

        .modal-shell {
            width: min(100%, 560px);
            border-radius: 26px;
            border: 1px solid rgba(var(--accent-color-rgb), 0.25);
            background:
                radial-gradient(circle at top, rgba(var(--accent-color-rgb), 0.16), transparent 40%),
                linear-gradient(180deg, rgba(12, 18, 26, 0.98), rgba(4, 7, 11, 0.99));
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.55), 0 0 36px rgba(var(--accent-color-rgb), 0.16);
            overflow: hidden;
        }

        .modal-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.07);
        }

        .modal-close {
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.05);
            color: #fff;
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .modal-art {
            background: radial-gradient(circle at top, rgba(var(--accent-color-rgb), 0.14), transparent 58%);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 20px;
        }

        .modal-art img {
            width: 100%;
            max-height: 260px;
            object-fit: contain;
            filter: drop-shadow(0 20px 24px rgba(0, 0, 0, 0.4));
        }
    </style>
</head>
<body>
    <div class="bg-grid"></div>
    <div class="bg-scan"></div>
    <div class="bg-vignette"></div>
    <div id="particles"></div>

    <main class="page-shell min-h-screen px-4 py-8 sm:px-6 lg:px-10">
        <div class="mx-auto flex w-full max-w-7xl flex-col gap-8">
            <header class="flex flex-col gap-5 text-center">
                <div class="space-y-3">
                    <div class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-[11px] tracking-[0.28em] text-white/60">
                        <span class="h-2 w-2 rounded-full bg-[var(--accent-color)] shadow-[0_0_12px_var(--accent-glow)]"></span>
                        POKÉDEX GRID / DIGITAL ARCHIVE
                    </div>
                    <h1 class="page-title text-3xl font-black text-white sm:text-5xl">POKÉDEX</h1>
                </div>
                <div class="divider mx-auto w-full max-w-3xl"></div>
                <div class="flex flex-wrap items-center justify-center gap-3 text-[11px] uppercase tracking-[0.22em] text-white/45">
                    <span class="meta-chip">{{ $totalMatches ?? 0 }} resultados</span>
                    <span class="meta-chip">{{ $selectedType ? strtoupper($selectedType) : 'TODOS OS TIPOS' }}</span>
                    <span class="meta-chip">ordenação {{ $sort === 'name' ? 'alfabética' : 'por ID' }}</span>
                </div>
            </header>

            <section class="ui-panel rounded-[28px] p-4 sm:p-6">
                <form action="{{ route('pokedex.index') }}" method="GET" class="space-y-4" id="pokedexFilterForm">
                    <div class="search-terminal border border-white/10 bg-black/35 p-4 sm:p-5">
                        <div class="grid gap-3 lg:grid-cols-[1.4fr_0.7fr_0.7fr_auto_auto]">
                            <input
                                type="text"
                                name="query"
                                id="pokedexSearchInput"
                                value="{{ request('query', $query ?? '') }}"
                                class="search-input"
                                placeholder="Pesquisar por nome ou número..."
                                autocomplete="off"
                                spellcheck="false"
                            >
                            <select name="type" class="filter-select" onchange="this.form.requestSubmit()">
                                <option value="">Todos os tipos</option>
                                @foreach($typeOptions as $type)
                                    <option value="{{ $type['value'] }}" @selected(($selectedType ?? '') === $type['value'])>{{ $type['label'] }}</option>
                                @endforeach
                            </select>
                            <select name="sort" class="filter-select" onchange="this.form.requestSubmit()">
                                <option value="id" @selected(($sort ?? 'id') === 'id')>Ordenar por ID</option>
                                <option value="name" @selected(($sort ?? 'id') === 'name')>Ordenar por nome</option>
                            </select>
                            <button type="submit" class="search-btn">Buscar</button>
                            <a href="{{ route('pokedex.index') }}" class="ghost-btn inline-flex items-center justify-center no-underline">Limpar</a>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <span class="filter-label mr-1 text-[10px] uppercase tracking-[0.28em] text-white/30">Filtros rápidos</span>
                        @foreach(['fire', 'water', 'grass', 'electric', 'psychic', 'dragon'] as $quickType)
                            <button
                                type="button"
                                class="quick-chip {{ ($selectedType ?? '') === $quickType ? 'is-active' : '' }}"
                                onclick="const form=this.closest('form'); form.querySelector('[name=type]').value='{{ $quickType }}'; form.submit();"
                            >
                                {{ $quickType }}
                            </button>
                        @endforeach
                    </div>
                </form>
            </section>

            <section>
                @if(empty($pokemonList))
                    <div class="empty-state flex flex-col items-center justify-center gap-4 px-6 py-16 text-center">
                        <div class="text-[11px] uppercase tracking-[0.35em] text-[var(--accent-color)]">Nenhum Pokémon encontrado</div>
                        <p class="max-w-xl text-sm leading-relaxed text-white/55">
                            Tente outro nome, número ou tipo. Você também pode limpar os filtros para voltar à grade completa.
                        </p>
                        <a href="{{ route('pokedex.index') }}" class="search-btn inline-flex items-center justify-center no-underline">Resetar filtros</a>
                    </div>
                @else
                    <div class="dex-grid" id="pokedexGrid">
                        @foreach($pokemonList as $pokemon)
                            <article
                                class="dex-card pokedex-card"
                                tabindex="0"
                                role="button"
                                aria-label="Abrir detalhes de {{ $pokemon['name'] ?? 'Pokémon' }}"
                                data-name="{{ strtolower($pokemon['name'] ?? '') }}"
                                data-id="{{ $pokemon['id'] ?? 0 }}"
                                data-image="{{ $pokemon['image'] ?? '' }}"
                                data-accent="{{ $pokemon['accentColor'] ?? $accentColor ?? '#7AC74C' }}"
                                data-accent-rgb="{{ $pokemon['accentColorRgb'] ?? $accentColorRgb ?? '122, 199, 76' }}"
                                data-types="{{ collect($pokemon['types'] ?? [])->pluck('label')->implode(' ') }}"
                                style="--pokemon-accent: {{ $pokemon['accentColor'] ?? $accentColor ?? '#7AC74C' }}; --pokemon-accent-rgb: {{ $pokemon['accentColorRgb'] ?? $accentColorRgb ?? '122, 199, 76' }};"
                            >
                                <div class="dex-card-topline"></div>
                                <div class="flex h-full flex-col gap-4 p-4 sm:p-5">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <div class="dex-id">#{{ str_pad((string) ($pokemon['id'] ?? 0), 3, '0', STR_PAD_LEFT) }}</div>
                                            <h2 class="dex-name mt-1">{{ $pokemon['name'] ?? 'Desconhecido' }}</h2>
                                        </div>
                                        <span class="meta-chip">GRID</span>
                                    </div>

                                    <div class="dex-art flex items-center justify-center p-4">
                                        <img
                                            src="{{ $pokemon['image'] ?? '' }}"
                                            alt="{{ $pokemon['name'] ?? 'Pokémon' }}"
                                            loading="lazy"
                                        >
                                    </div>

                                    <div class="flex flex-wrap gap-2">
                                        @foreach($pokemon['types'] ?? [] as $type)
                                            <span class="type-chip">{{ $type['label'] ?? 'NORMAL' }}</span>
                                        @endforeach
                                    </div>

                                    <div class="mt-auto flex items-center justify-between border-t border-white/8 pt-3">
                                        <span class="dex-caption text-[10px] uppercase tracking-[0.2em] text-white/35">Pokémon listado</span>
                                        <span class="dex-caption text-[10px] uppercase tracking-[0.2em] text-[var(--pokemon-accent)]">Dex entry</span>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                    <div class="empty-state mt-6 hidden px-6 py-14 text-center" id="pokedexEmptyLiveState">
                        <div class="text-[11px] uppercase tracking-[0.35em] text-[var(--accent-color)]">Nenhum resultado na busca</div>
                        <p class="mt-3 text-sm leading-relaxed text-white/55">
                            Tente outro termo ou limpe a busca para voltar a ver a grade.
                        </p>
                    </div>
                @endif
            </section>

            <footer class="status-bar pb-2 text-center text-[8px] sm:text-[9px]">
                DADOS: POKEAPI.CO &nbsp;//&nbsp; POKÉDEX GRID &nbsp;//&nbsp; VISUAL INSPIRADO NA TCGDEX
            </footer>

            <div class="flex justify-center pb-4">
                <a href="{{ route('pokemon.index') }}" class="ghost-btn inline-flex items-center justify-center no-underline">
                    Abrir TCGDEX
                </a>
            </div>
        </div>
    </main>

    <div class="modal-overlay" id="pokemonModal" aria-hidden="true">
        <div class="modal-shell">
            <div class="modal-header flex items-center justify-between gap-4 p-4 sm:p-5">
                <div>
                    <div class="dex-id" id="modalPokemonId">#000</div>
                    <h3 class="dex-name mt-1 text-lg sm:text-2xl" id="modalPokemonName">Pokémon</h3>
                </div>
                <button type="button" class="modal-close" id="modalCloseButton" aria-label="Fechar modal">×</button>
            </div>

            <div class="grid gap-5 p-4 sm:p-5 lg:grid-cols-[1.1fr_0.9fr]">
                <div class="modal-art flex items-center justify-center p-5" id="modalPokemonArtWrap">
                    <img src="" alt="Pokémon" id="modalPokemonImage">
                </div>

                <div class="flex flex-col gap-4">
                    <div>
                        <div class="text-[10px] uppercase tracking-[0.3em] text-white/35">Tipos</div>
                        <div class="mt-2 flex flex-wrap gap-2" id="modalPokemonTypes"></div>
                    </div>

                    <div class="rounded-2xl border border-white/8 bg-white/3 p-4">
                        <div class="text-[10px] uppercase tracking-[0.3em] text-white/35">Ação rápida</div>
                        <p class="mt-2 text-sm leading-relaxed text-white/70">
                            Clique fora do modal ou no botão de fechar para voltar à Pokédex.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const container = document.getElementById('particles');
            for (let i = 0; i < 16; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + 'vw';
                particle.style.animationDuration = (10 + Math.random() * 10) + 's';
                particle.style.animationDelay = (Math.random() * 8) + 's';
                particle.style.opacity = 0.18 + Math.random() * 0.55;
                particle.style.width = particle.style.height = (1 + Math.random() * 2.5) + 'px';
                container.appendChild(particle);
            }
        })();

        (function () {
            const searchInput = document.getElementById('pokedexSearchInput');
            const form = document.getElementById('pokedexFilterForm');
            const cards = Array.from(document.querySelectorAll('.pokedex-card'));
            const emptyState = document.getElementById('pokedexEmptyLiveState');
            const resultsLabel = document.querySelector('.meta-chip');
            const modal = document.getElementById('pokemonModal');
            const modalCloseButton = document.getElementById('modalCloseButton');
            const modalPokemonId = document.getElementById('modalPokemonId');
            const modalPokemonName = document.getElementById('modalPokemonName');
            const modalPokemonImage = document.getElementById('modalPokemonImage');
            const modalPokemonTypes = document.getElementById('modalPokemonTypes');
            const modalArtWrap = document.getElementById('modalPokemonArtWrap');

            function openModalFromCard(card) {
                const name = card.dataset.name || 'pokemon';
                const id = parseInt(card.dataset.id || '0', 10);
                const image = card.dataset.image || '';
                const accent = card.dataset.accent || '#7AC74C';
                const accentRgb = card.dataset.accentRgb || '122, 199, 76';
                const types = (card.dataset.types || '').split(' ').filter(Boolean);

                modal.style.setProperty('--accent-color', accent);
                modal.style.setProperty('--accent-color-rgb', accentRgb);
                modalPokemonId.textContent = '#' + String(id).padStart(3, '0');
                modalPokemonName.textContent = name.charAt(0).toUpperCase() + name.slice(1);
                modalPokemonImage.src = image;
                modalPokemonImage.alt = name;
                modalArtWrap.style.background = 'radial-gradient(circle at top, rgba(' + accentRgb + ', 0.14), transparent 58%)';

                modalPokemonTypes.innerHTML = '';
                types.forEach((type) => {
                    const chip = document.createElement('span');
                    chip.className = 'type-chip';
                    chip.textContent = type;
                    modalPokemonTypes.appendChild(chip);
                });

                modal.classList.add('is-open');
                modal.setAttribute('aria-hidden', 'false');
            }

            function closeModal() {
                modal.classList.remove('is-open');
                modal.setAttribute('aria-hidden', 'true');
            }

            function normalize(value) {
                return String(value || '').toLowerCase().trim();
            }

            function filterCards() {
                const query = normalize(searchInput ? searchInput.value : '');
                let visibleCount = 0;

                cards.forEach((card) => {
                    const haystack = normalize(card.dataset.name) + ' ' + normalize(card.dataset.id) + ' ' + normalize(card.dataset.types);
                    const matches = haystack.includes(query);
                    card.style.display = matches ? '' : 'none';
                    if (matches) {
                        visibleCount += 1;
                    }
                });

                if (emptyState) {
                    emptyState.classList.toggle('hidden', visibleCount !== 0);
                }

                if (resultsLabel) {
                    resultsLabel.textContent = visibleCount + ' resultados';
                }
            }

            if (searchInput) {
                searchInput.addEventListener('input', filterCards);
                filterCards();
            }

            cards.forEach((card) => {
                card.addEventListener('click', () => openModalFromCard(card));
                card.addEventListener('keydown', (event) => {
                    if (event.key === 'Enter' || event.key === ' ') {
                        event.preventDefault();
                        openModalFromCard(card);
                    }
                });
            });

            if (modalCloseButton) {
                modalCloseButton.addEventListener('click', closeModal);
            }

            if (modal) {
                modal.addEventListener('click', (event) => {
                    if (event.target === modal) {
                        closeModal();
                    }
                });
            }

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    closeModal();
                }
            });
        })();
    </script>
</body>
</html>
