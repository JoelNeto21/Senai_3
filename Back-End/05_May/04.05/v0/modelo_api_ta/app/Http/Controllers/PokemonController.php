<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Pool;

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
            return back()
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
            if ($entry['language']['name'] === 'pt-BR') {
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
            if ($gen['language']['name'] === 'pt-BR') {
                $genus = $gen['genus'];
                break;
            }
            if ($gen['language']['name'] === 'en' && $genus === "Pokémon Desconhecido") {
                $genus = $gen['genus'];
            }
        }

        // Injeta dados limpos no array principal (o Blade não precisa fazer lógica de busca)
        $pokemon['species_data'] = [
            'flavor_text' => $flavorText,
            'genus'       => $genus,
        ];

        return view('pokemon', compact('pokemon'));
    }
}