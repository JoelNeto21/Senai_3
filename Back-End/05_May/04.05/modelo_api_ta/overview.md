# Overview - modelo_api_ta

Este documento explica, passo a passo, como recriar o projeto Laravel usado neste repositório em um ambiente Windows com Laragon. Inclui comandos, configuração mínima e versões simples de código para os arquivos essenciais (foco funcional, não estético).

## Requisitos

- Laragon instalado (https://laragon.org/) com PHP >= 8.1
- Composer disponível (vem com Laragon)
- Extensões PHP recomendadas: `mbstring`, `curl`, `fileinfo`, `gd` (para imagens)

## 1. Criando o projeto (Laragon)

1. Abra Laragon → Menu → Quick App → Composer → `laravel new modelo_api_ta` (ou use terminal):

```bash
cd C:/laragon/www
composer create-project --prefer-dist laravel/laravel modelo_api_ta
```

2. Entre na pasta e configure permissões (Windows/Laragon geralmente já ok):

```bash
cd modelo_api_ta
```

3. Crie o banco de dados no MySQL/MariaDB via Laragon (ex.: `modelo_api_ta`) e atualize `.env`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=modelo_api_ta
DB_USERNAME=root
DB_PASSWORD=
```

## 2. Comandos iniciais

```bash
composer install
php artisan key:generate
php artisan migrate
php artisan storage:link
php artisan serve
```

## 3. Estrutura mínima necessária (o que deve existir)

- `routes/web.php` — rotas web principais
- `app/Http/Controllers/PokemonController.php` — controller principal
- `app/Models/PokemonLocal.php` — model para imagens locais
- `database/migrations/*_create_pokemon_locals_table.php` — migration
- `resources/views/*.blade.php` — views básicas (`pokemon.blade.php`, `pokedex.blade.php`, `pokemon-local-create.blade.php`)

As próximas seções mostram versões simples e funcionais desses arquivos.

## 4. `routes/web.php` (exemplo mínimo)

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PokemonController;

Route::get('/tcgdex', [PokemonController::class, 'index']);
Route::get('/pokemon/search', [PokemonController::class, 'search']);
Route::get('/pokedex', [PokemonController::class, 'pokedex']);

Route::get('/pokemon-local/novo', [PokemonController::class, 'createLocal']);
Route::post('/pokemon-local/salvar', [PokemonController::class, 'storeLocal']);

// Rotas didáticas
Route::get('/pokemon/{name}', function ($name) {
    return response()->json(['name' => $name]);
});

```

## 5. `app/Models/PokemonLocal.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PokemonLocal extends Model
{
    protected $table = 'pokemon_locals';
    protected $fillable = ['nome', 'caminho_imagem'];
}
```

## 6. Migration (exemplo): `database/migrations/2026_05_08_create_pokemon_locals_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('pokemon_locals', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('caminho_imagem');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pokemon_locals');
    }
};
```

## 7. Controller mínimo: `app/Http/Controllers/PokemonController.php`

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\PokemonLocal;

class PokemonController extends Controller
{
    public function index()
    {
        $id = rand(1, 1025);
        return $this->fetchAndRender($id);
    }

    public function search(Request $request)
    {
        $q = trim($request->query('query', ''));
        if ($q === '') return redirect('/tcgdex');
        return $this->fetchAndRender($q);
    }

    public function pokedex(Request $request)
    {
        // Exemplo simples: lista IDs 1..20
        $ids = range(1, 20);
        $pokemons = [];
        foreach ($ids as $id) {
            $res = Http::get("https://pokeapi.co/api/v2/pokemon/{$id}");
            if ($res->ok()) $pokemons[] = $res->json();
        }
        return view('pokedex', ['pokemons' => $pokemons]);
    }

    public function createLocal()
    {
        return view('pokemon-local-create');
    }

    public function storeLocal(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'imagem' => 'required|image|max:2048',
        ]);
        $path = $request->file('imagem')->store('pokemons_locais', 'public');
        PokemonLocal::create(['nome' => $data['nome'], 'caminho_imagem' => $path]);
        return redirect('/pokemon-local/novo')->with('success', 'Salvo');
    }

    protected function fetchAndRender($identifier)
    {
        $res = Http::get("https://pokeapi.co/api/v2/pokemon/{$identifier}");
        if (!$res->ok()) return redirect('/tcgdex')->withErrors(['notfound' => 'Pokemon nao encontrado']);
        $pokemon = $res->json();
        return view('pokemon', ['pokemon' => $pokemon]);
    }
}
```

## 8. Views mínimas

- `resources/views/pokemon.blade.php` (simples):

```html
<!doctype html>
<html>
<body>
  <h1>{{ $pokemon['name'] ?? '---' }}</h1>
  <img src="{{ $pokemon['sprites']['front_default'] ?? '' }}" alt="">
  <pre>{{ json_encode($pokemon, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
</body>
</html>
```

- `resources/views/pokedex.blade.php`:

```html
<!doctype html>
<html>
<body>
  <h1>Pokedex (exemplo)</h1>
  <ul>
    @foreach($pokemons as $p)
      <li>{{ $p['id'] }} - {{ $p['name'] }}</li>
    @endforeach
  </ul>
</body>
</html>
```

- `resources/views/pokemon-local-create.blade.php`:

```html
<!doctype html>
<html>
<body>
  @if(session('success'))<p>{{ session('success') }}</p>@endif
  <form action="/pokemon-local/salvar" method="post" enctype="multipart/form-data">
    @csrf
    <label>Nome: <input type="text" name="nome"></label>
    <label>Imagem: <input type="file" name="imagem"></label>
    <button type="submit">Salvar</button>
  </form>
</body>
</html>
```

## 9. Observações e dicas rápidas

- Para chamadas HTTP usamos `Illuminate\Support\Facades\Http` (já incluído no Laravel 8+).
- Ajuste validações e tamanhos conforme necessidade.
- Imagens ficam em `storage/app/public/pokemons_locais` e são servidas via `public/storage/pokemons_locais` após `php artisan storage:link`.

## 10. Comandos úteis finais

```bash
php artisan migrate
php artisan storage:link
php artisan serve
```

---

Se quiser, eu posso:
- gerar esses arquivos automaticamente no repositório, ou
- rodar checks básicos para validar sintaxe PHP/Blade.

