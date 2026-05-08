<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Http\Controllers\PokemonController;

// Adicione o ->name('pokemon.index') na rota que lista os Pokémons
Route::get('/pokemon', [PokemonController::class, 'index'])->name('pokemon.index');

// (E logo abaixo deve estar a rota de busca que você consertou antes)
Route::get('/pokemon/search', [PokemonController::class, 'search'])->name('pokemon.search');

//Exemplo 1 - GET

Route::get('/pokemon/{name}', function ($name) {
    $response = Http::get("https://pokeapi.co/api/v2/pokemon/{$name}");

    if ($response->successful()) {
        $dados = $response->json();
        return response()->json([
            'status' => 'Conectado com Sucesso!',
            'resultado' => [
                'identificador' => $dados['id'],
                'nome_pokemon' => ucfirst($dados['name']),
                'foto' => $dados['sprites']['front_default']
            ]
        ], 200);
        return response()->json(['erro' => 'Pokemon não encontrado'], 404);
    };
});

Route::get('/user/{firstName}', function ($firstName) {
    $response = Http::get("https://dummyjson.com/user/search?q={$firstName}");

    if ($response->successful()) {
        $dados = $response->json();
        $entrar = $dados['users'][0];

        return response()->json([
            'status' => 'Conectado com Sucesso!',
            'resultado' => [
                'nome_usuario' => $entrar['firstName'],
                'sobrenome_usuario' => $entrar['lastName']
            ]
        ], 200);
    };
    return response()->json(['erro' => 'Usuario não encontrado'], 404);
});


//Exemplo 2 - POST

Route::post('/pokemon/novo', function (Request $request) {
    $dados = $request->validate([
        'nome' => 'required|string|min:3',
        'tipo' => 'required|string',
        'ataque' => 'required|integer'
    ]);

    return response()->json([
        'memsagem' => 'Pokemon cadastrado com sucesso!',
        'id_gerado' => rand(1000, 9999),
        'dados_recebidos' => $dados
    ], 201);
});


Route::post('/user/novo', function (Request $request) {
    $dados = $request->validate([
        'nome' => 'required|string|min:3',
        'sobrenome' => 'required|string'
    ]);

    return response()->json([
        'mensagem' => 'Usuario cadastrado com sucesso!',
        'id_gerado' => rand(1000, 9999),
        'dados_recebidos' => $dados
    ], 201);
});

Route::get('/', function () {
    return view('welcome');
});
