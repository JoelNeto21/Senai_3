<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Storage;
use App\Models\PokemonLocal; 

class PokemonController extends Controller
{
    /**
     * Exibe um Pokémon aleatório (rota: GET /pokemon)
     */
    public function index()
    {
        $id = rand(1, 1025);
        return $this->fetchAndRender($id);
    }

    /**
     * Exibe a Pokédex em grade com filtros (rota: GET /pokedex)
     */
    public function pokedex(Request $request)
    {
        $query = trim(strtolower($request->input('query', '')));
        $selectedType = trim(strtolower($request->input('type', '')));
        $sort = trim(strtolower($request->input('sort', 'id')));
        $sort = in_array($sort, ['id', 'name'], true) ? $sort : 'id';
        $baseUrl = 'https://pokeapi.co/api/v2';
        $perPage = 36;

        $typeOptions = $this->getTypeOptions($baseUrl);
        $candidatePokemon = $this->getCandidatePokemon($baseUrl, $selectedType);

        if ($query !== '') {
            $candidatePokemon = array_values(array_filter($candidatePokemon, function (array $pokemon) use ($query) {
                $name = strtolower($pokemon['name'] ?? '');
                $identifier = $this->extractPokemonIdentifier($pokemon['url'] ?? '');

                return str_contains($name, $query) || $identifier === ltrim($query, '0');
            }));
        }

        usort($candidatePokemon, function (array $left, array $right) use ($sort) {
            if ($sort === 'name') {
                return strcmp($left['name'] ?? '', $right['name'] ?? '');
            }

            $leftId = (int) $this->extractPokemonIdentifier($left['url'] ?? '');
            $rightId = (int) $this->extractPokemonIdentifier($right['url'] ?? '');

            return $leftId <=> $rightId;
        });

        $totalMatches = count($candidatePokemon);
        $candidatePokemon = array_slice($candidatePokemon, 0, $perPage);
        $pokemonList = $this->hydratePokemonList($candidatePokemon);

        $accentType = $selectedType;
        if ($accentType === '' && !empty($pokemonList)) {
            $accentType = $pokemonList[0]['types'][0]['name'] ?? 'normal';
        }

        $palette = $this->resolveTypePalette($accentType ?: 'normal');

        return view('pokedex', [
            'pokemonList' => $pokemonList,
            'query' => $query,
            'selectedType' => $selectedType,
            'sort' => $sort,
            'typeOptions' => $typeOptions,
            'totalMatches' => $totalMatches,
            'accentColor' => $palette['hex'],
            'accentColorRgb' => $palette['rgb'],
        ]);
    }

    /**
     * Busca por nome ou número (rota: GET /pokemon/search?query=pikachu)
     */
    public function search(Request $request)
    {
        $query = trim(strtolower($request->input('query', '')));

        if (empty($query)) {
            return redirect()->route('pokemon.index');
        }

        // Valida: aceita nome (letras/hífen) ou número (1–1025)
        if (is_numeric($query)) {
            $id = (int) $query;
            if ($id < 1 || $id > 1025) {
                return back()->withErrors(['query' => 'Número de Pokémon inválido. Use entre 1 e 1025.']);
            }
            return $this->fetchAndRender($id);
        }

        // Por nome — a PokéAPI aceita o nome direto na URL
        return $this->fetchAndRender($query);
    }

    /**
     * Faz as requisições paralelas e renderiza a view.
     *
     * @param string|int $identifier  Nome ou ID do Pokémon
     */
    private function fetchAndRender(string|int $identifier)
    {
        $baseUrl = "https://pokeapi.co/api/v2";

        $responses = Http::pool(fn (Pool $pool) => [
            $pool->as('pokemon')->get("{$baseUrl}/pokemon/{$identifier}"),
            $pool->as('species')->get("{$baseUrl}/pokemon-species/{$identifier}"),
        ]);

        // Se o Pokémon não foi encontrado (ex: nome errado)
        if (!$responses['pokemon']->successful()) {
            return redirect()
                ->route('pokemon.index')
                ->withErrors(['query' => "Pokémon \"{$identifier}\" não encontrado. Tente outro nome ou número."])
                ->withInput();
        }

        $pokemon     = $responses['pokemon']->json();
        $speciesData = $responses['species']->successful()
            ? $responses['species']->json()
            : [];

        // ── Flavor Text (PT-BR > EN) ──
        $flavorText = "Descrição não disponível.";
        foreach ($speciesData['flavor_text_entries'] ?? [] as $entry) {
            if ($entry['language']['name'] === 'pt-br') {
                $flavorText = str_replace(["\n", "\f", "\r"], " ", $entry['flavor_text']);
                break;
            }
            if ($entry['language']['name'] === 'en' && $flavorText === "Descrição não disponível.") {
                $flavorText = str_replace(["\n", "\f", "\r"], " ", $entry['flavor_text']);
            }
        }

        // ── Genus (PT-BR > EN) ──
        $genus = "Pokémon Desconhecido";
        foreach ($speciesData['genera'] ?? [] as $gen) {
            if ($gen['language']['name'] === 'pt-br') {
                $genus = $gen['genus'];
                break;
            }
            if ($gen['language']['name'] === 'en' && $genus === "Pokémon Desconhecido") {
                $genus = $gen['genus'];
            }
        }

        // ── Variações (Alola, Mega, G-Max, etc) ──
        $variations = $this->getVariations($speciesData, $pokemon['id'] ?? 1);

        // Injeta dados limpos no array principal (o Blade não precisa fazer lógica de busca)
        $pokemon['species_data'] = [
            'flavor_text' => $flavorText,
            'genus'       => $genus,
        ];

        $pokemon['variations'] = $variations;

        return view('pokemon', compact('pokemon'));
    }

    /**
     * Busca a lista base de Pokémon para a grade.
     */
    private function getCandidatePokemon(string $baseUrl, string $selectedType): array
    {
        if ($selectedType !== '') {
            $response = Http::get("{$baseUrl}/type/{$selectedType}");

            if (!$response->successful()) {
                return [];
            }

            $entries = $response->json()['pokemon'] ?? [];

            return array_values(array_map(fn (array $entry) => $entry['pokemon'] ?? [], $entries));
        }

        $response = Http::get("{$baseUrl}/pokemon?limit=1025&offset=0");

        if (!$response->successful()) {
            return [];
        }

        return $response->json()['results'] ?? [];
    }

    /**
     * Carrega os detalhes dos Pokémon que serão renderizados na grid.
     */
    private function hydratePokemonList(array $candidatePokemon): array
    {
        if (empty($candidatePokemon)) {
            return [];
        }

        $responses = Http::pool(function (Pool $pool) use ($candidatePokemon) {
            $requests = [];

            foreach ($candidatePokemon as $pokemon) {
                if (empty($pokemon['name']) || empty($pokemon['url'])) {
                    continue;
                }

                $requests[$pokemon['name']] = $pool->as($pokemon['name'])->get($pokemon['url']);
            }

            return $requests;
        });

        $pokemonList = [];

        foreach ($candidatePokemon as $pokemon) {
            $name = $pokemon['name'] ?? null;

            if (!$name || !isset($responses[$name]) || !$responses[$name]->successful()) {
                continue;
            }

            $data = $responses[$name]->json();
            $primaryType = $data['types'][0]['type']['name'] ?? 'normal';
            $palette = $this->resolveTypePalette($primaryType);

            $pokemonList[] = [
                'id' => $data['id'] ?? 0,
                'name' => ucfirst($data['name'] ?? 'Desconhecido'),
                'slug' => $data['name'] ?? '',
                'image' => $data['sprites']['other']['official-artwork']['front_default']
                    ?? $data['sprites']['front_default']
                    ?? '',
                'types' => array_map(fn (array $type) => [
                    'name' => $type['type']['name'] ?? 'normal',
                    'label' => strtoupper($type['type']['name'] ?? 'NORMAL'),
                ], $data['types'] ?? []),
                'accentColor' => $palette['hex'],
                'accentColorRgb' => $palette['rgb'],
            ];
        }

        usort($pokemonList, fn (array $left, array $right) => ($left['id'] ?? 0) <=> ($right['id'] ?? 0));

        return $pokemonList;
    }

    /**
     * Carrega as opções de tipo para o filtro.
     */
    private function getTypeOptions(string $baseUrl): array
    {
        $response = Http::get("{$baseUrl}/type");

        if (!$response->successful()) {
            return $this->defaultTypeOptions();
        }

        $types = array_filter($response->json()['results'] ?? [], function (array $type) {
            return !in_array($type['name'] ?? '', ['unknown', 'shadow'], true);
        });

        return array_map(fn (array $type) => [
            'value' => $type['name'] ?? '',
            'label' => ucfirst($type['name'] ?? ''),
        ], array_values($types));
    }

    /**
     * Fallback caso a API de tipos falhe.
     */
    private function defaultTypeOptions(): array
    {
        return [
            ['value' => 'normal', 'label' => 'Normal'],
            ['value' => 'fire', 'label' => 'Fire'],
            ['value' => 'water', 'label' => 'Water'],
            ['value' => 'grass', 'label' => 'Grass'],
            ['value' => 'electric', 'label' => 'Electric'],
            ['value' => 'ice', 'label' => 'Ice'],
            ['value' => 'fighting', 'label' => 'Fighting'],
            ['value' => 'poison', 'label' => 'Poison'],
            ['value' => 'ground', 'label' => 'Ground'],
            ['value' => 'flying', 'label' => 'Flying'],
            ['value' => 'psychic', 'label' => 'Psychic'],
            ['value' => 'bug', 'label' => 'Bug'],
            ['value' => 'rock', 'label' => 'Rock'],
            ['value' => 'ghost', 'label' => 'Ghost'],
            ['value' => 'dragon', 'label' => 'Dragon'],
            ['value' => 'dark', 'label' => 'Dark'],
            ['value' => 'steel', 'label' => 'Steel'],
            ['value' => 'fairy', 'label' => 'Fairy'],
        ];
    }

    /**
     * Resolve a cor visual por tipo.
     */
    private function resolveTypePalette(string $type): array
    {
        $colors = [
            'normal' => ['hex' => '#A8A77A', 'rgb' => '168, 167, 122'],
            'fire' => ['hex' => '#EE8130', 'rgb' => '238, 129, 48'],
            'water' => ['hex' => '#6390F0', 'rgb' => '99, 144, 240'],
            'electric' => ['hex' => '#F7D02C', 'rgb' => '247, 208, 44'],
            'grass' => ['hex' => '#7AC74C', 'rgb' => '122, 199, 76'],
            'ice' => ['hex' => '#96D9D6', 'rgb' => '150, 217, 214'],
            'fighting' => ['hex' => '#C22E28', 'rgb' => '194, 46, 40'],
            'poison' => ['hex' => '#A33EA1', 'rgb' => '163, 62, 161'],
            'ground' => ['hex' => '#E2BF65', 'rgb' => '226, 191, 101'],
            'flying' => ['hex' => '#A98FF3', 'rgb' => '169, 143, 243'],
            'psychic' => ['hex' => '#F95587', 'rgb' => '249, 85, 135'],
            'bug' => ['hex' => '#A6B91A', 'rgb' => '166, 185, 26'],
            'rock' => ['hex' => '#B6A136', 'rgb' => '182, 161, 54'],
            'ghost' => ['hex' => '#735797', 'rgb' => '115, 87, 151'],
            'dragon' => ['hex' => '#6F35FC', 'rgb' => '111, 53, 252'],
            'dark' => ['hex' => '#705746', 'rgb' => '112, 87, 70'],
            'steel' => ['hex' => '#B7B7CE', 'rgb' => '183, 183, 206'],
            'fairy' => ['hex' => '#D685AD', 'rgb' => '214, 133, 173'],
        ];

        return $colors[$type] ?? $colors['normal'];
    }

    /**
     * Extrai as variações disponíveis de um Pokémon (Alola, Mega, G-Max, etc).
     */
    private function getVariations(array $speciesData, int $pokemonId): array
    {
        $variations = [];

        // Busca as formas (varieties) da espécie
        $varieties = $speciesData['varieties'] ?? [];

        if (empty($varieties)) {
            return $variations;
        }

        // Requisições em paralelo para as formas
        $formUrls = array_map(fn (array $variety) => $variety['pokemon']['url'] ?? '', $varieties);
        $formUrls = array_filter($formUrls);

        if (empty($formUrls)) {
            return $variations;
        }

        $responses = Http::pool(function (Pool $pool) use ($formUrls) {
            $requests = [];
            foreach ($formUrls as $index => $url) {
                $requests["form_{$index}"] = $pool->as("form_{$index}")->get($url);
            }
            return $requests;
        });

        // Processa as respostas
        foreach ($responses as $key => $response) {
            if (!$response->successful()) {
                continue;
            }

            $form = $response->json();
            $formName = $form['name'] ?? '';
            $formId = $form['id'] ?? 0;

            // Tenta extrair o tipo de variação do nome
            $variationType = $this->detectVariationType($formName);

            // Tenta obter imagens
            $imgDefault = $form['sprites']['other']['official-artwork']['front_default']
                ?? $form['sprites']['front_default']
                ?? null;

            $imgShiny = $form['sprites']['other']['official-artwork']['front_shiny']
                ?? $form['sprites']['front_shiny']
                ?? $imgDefault;

            if ($imgDefault) {
                $variations[] = [
                    'key' => $variationType,
                    'label' => ucfirst(str_replace('-', ' ', $variationType)),
                    'name' => $formName,
                    'image' => $imgDefault,
                    'image_shiny' => $imgShiny,
                    'form_id' => $formId,
                ];
            }
        }

        return $variations;
    }

    /**
     * Detecta o tipo de variação pelo nome da forma.
     */
    private function detectVariationType(string $formName): string
    {
        $formName = strtolower($formName);

        if (str_contains($formName, 'alola')) return 'alola';
        if (str_contains($formName, 'galar')) return 'galar';
        if (str_contains($formName, 'paldea')) return 'paldea';
        if (str_contains($formName, 'mega')) return 'mega';
        if (str_contains($formName, 'gigantamax') || str_contains($formName, 'gmax')) return 'gmax';
        if (str_contains($formName, 'hisui')) return 'hisui';
        if (str_contains($formName, 'armored')) return 'armored';
        if (str_contains($formName, 'crowned')) return 'crowned';
        if (str_contains($formName, 'unbound')) return 'unbound';

        return 'variant';
    }

    /**
     * Extrai o identificador numérico da URL da API.
     */
    private function extractPokemonIdentifier(string $url): string
    {
        $segments = array_values(array_filter(explode('/', trim($url, '/'))));
        $identifier = (string) (int) (end($segments) ?: 0);

        return $identifier;
    }

    // =====================================================================
    // NOVAS FUNÇÕES PARA ARMAZENAMENTO LOCAL (NÃO INTERFEREM NA POKEAPI)
    // =====================================================================

    /**
     * Exibe o formulário para salvar um Pokémon com imagem local
     * (rota: GET /pokemon-local/novo)
     */
    public function createLocal()
    {
        $pokemonLocais = PokemonLocal::query()
            ->latest()
            ->get();

        return view('pokemon-local-create', compact('pokemonLocais'));
    }

    /**
     * Recebe os dados do formulário, salva a imagem na pasta e o caminho no banco
     * (rota: POST /pokemon-local/salvar)
     */
    public function storeLocal(Request $request)
    {
        // Valida se o nome foi preenchido e se o arquivo é realmente uma imagem
        $request->validate([
            'nome' => 'required|string|max:255',
            'imagem' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Salva a imagem na pasta: storage/app/public/pokemons_locais
        $caminhoImagem = $request->file('imagem')->store('pokemons_locais', 'public');

        // Salva o nome e o caminho da imagem no banco de dados
        PokemonLocal::create([
            'nome' => $request->nome,
            'caminho_imagem' => $caminhoImagem
        ]);

        return redirect()
            ->route('pokemon-local.create')
            ->with('sucesso', 'Pokémon salvo localmente com sucesso!');
    }

    /**
     * Atualiza nome e/ou imagem de um Pokémon salvo localmente.
     */
    public function updateLocal(Request $request, PokemonLocal $pokemonLocal)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $caminhoImagem = $pokemonLocal->caminho_imagem;

        if ($request->hasFile('imagem')) {
            if ($caminhoImagem && Storage::disk('public')->exists($caminhoImagem)) {
                Storage::disk('public')->delete($caminhoImagem);
            }

            $caminhoImagem = $request->file('imagem')->store('pokemons_locais', 'public');
        }

        $pokemonLocal->update([
            'nome' => $validated['nome'],
            'caminho_imagem' => $caminhoImagem,
        ]);

        return redirect()
            ->route('pokemon-local.create')
            ->with('sucesso', 'Pokémon atualizado com sucesso!');
    }

    /**
     * Exclui um Pokémon salvo localmente.
     */
    public function destroyLocal(PokemonLocal $pokemonLocal)
    {
        if ($pokemonLocal->caminho_imagem && Storage::disk('public')->exists($pokemonLocal->caminho_imagem)) {
            Storage::disk('public')->delete($pokemonLocal->caminho_imagem);
        }

        $pokemonLocal->delete();

        return redirect()
            ->route('pokemon-local.create')
            ->with('sucesso', 'Pokémon excluído com sucesso!');
    }
}