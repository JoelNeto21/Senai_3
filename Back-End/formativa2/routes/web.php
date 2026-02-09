<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/empresa', function () {
    return view('site/empresa');
});

// Qualquer tipo de request
Route::any('/any', function () {
    return "Permite todo o conteúdo GET, POST, PUT, DELETE";
});

// Parâmetros específicos GET/POST
// Route::match(['get', 'post'], '/match', function () {
//     return "Permite acessos definidos";
// });

// Parâmetros específicos PUT/DELETE
Route::match(['put', 'delete'], '/match', function () {
    return "Permite acessos definidos";
});

// Recebendo variáveis pela URL
// Route::get('/produto/{id}', function ($id) {
//     return "O ID do produto é: " .$id;
// });

// Recebendo variáveis pela URL
Route::get('/produto/{id}/{nome?}', function ($id, $nome = '') {
    return "O ID do produto é: " . $id . "<br>". "O produto é: " . $nome;
});

//Redirecionar rotas
// Route::get('/sobre', function () {
//     return redirect('/empresa');
// });

//Redirecionar rotas
Route::redirect('/sobre', '/empresa');

// Criar nome para rotas
Route::get('/news', function () {
    return view('news');
})->name('noticias');

// Acesso pelo nome criado
Route::get('/novidades', function () {
    return redirect()->route('noticias');
});
