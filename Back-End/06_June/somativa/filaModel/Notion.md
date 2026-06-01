# Desenvolvimento ERP Confecção: Do Zero à Governança Corporativa

Este documento é o guia definitivo e centralizado para a construção do ecossistema ERP da nossa Confecção Têxtil utilizando **Laravel 11**, **Filament v5 (Stack TALL)**, **Spatie Laravel Permission (ACL)** e **Mailpit**.

## 💻 MÓDULO 0: Setup do Projeto & Infraestrutura Local

### 1. Criando o Esqueleto do Projeto

Certifique-se de estar com o PHP 8.2+ e o Composer instalados localmente. No terminal, execute:

Bash 
```
composer create-project laravel/laravel confeccao
cd confeccao
```

### 2. Configuração do Ambiente e Banco de Dados (`.env`)

Abra o projeto no seu editor de código e configure o arquivo `.env` localizado na raiz. Ajustaremos as credenciais do banco de dados (certifique-se de criar o banco de dados vazio chamado `confeccao` no seu MySQL/PostgreSQL local) e apontaremos o driver de e-mail para a porta do **Mailpit**:

Snippet de código

```
APP_NAME="ERP Confecção"
APP_ENV=local
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=confeccao
DB_USERNAME=root
DB_PASSWORD=sua_senha_aqui

MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="sistema@confeccao.com"
MAIL_FROM_NAME="ERP Confecção Têxtil"
```

O Mailpit será o nosso servidor SMTP local falso para interceptar e auditar e-mails em ambiente de desenvolvimento.

- **Se usa Laragon (Windows):** Baixe o executável em GitHub do Mailpit, extraia o arquivo `mailpit.exe` e jogue-o na pasta `C:\laragon\bin\mailpit\`. O Laragon passará a iniciá-lo automaticamente.
- **Se usa macOS (via Homebrew):** Execute `brew install mailpit` e depois `brew services start mailpit`.
- **Se usa Linux / Outros (via terminal direto):** Execute `mailpit` no terminal para ligar o serviço.

> 📬 **Painel do Mailpit:** A interface web para ler os e-mails capturados fica acessível em: **`http://localhost:8025`**.
> 

## 🛠️ MÓDULO 1: Instalação do Painel & Motores Core

No terminal do seu projeto, execute a instalação do core do Filament v5 e, logo em seguida, o motor de permissões da Spatie:
Bash

```
# 1. Instalar o Filament v5
composer require filament/filament:"^5" -W
php artisan filament:install --panels

# 2. Instalar o Spatie Laravel Permission
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"

# 3. Limpar caches estruturais e rodar as primeiras migrações base
php artisan optimize:clear
php artisan migrate
```
### Criar o Primeiro Usuário Administrador

Gere as suas credenciais de acesso ao painel através do comando interativo:

Bash


```
php artisan make:filament-user
```

> 🌐 **Acesso:** Inicialize o servidor com `php artisan serve` e acesse **`http://localhost:8000/admin`**.
> 

## 🗄️ MÓDULO 2: Modelagem de Dados Relacional (Migrations & Models)

Antes de desenharmos as telas visuais, precisamos estruturar como o nosso banco de dados vai conversar. Vamos criar os modelos e as tabelas de migração de forma simultânea. Execute os comandos abaixo no terminal:

Bash

# 

```
php artisan make:model Cliente -m
php artisan make:model Fornecedor -m
php artisan make:model Insumo -m
php artisan make:model Produto -m
php artisan make:model Pedido -m
php artisan make:model ItemPedido -m
php artisan make:model MovimentacaoEstoque -m
```

### 1. Configurando os Arquivos de Migração (`database/migrations/`)

Localize os respectivos arquivos novos criados no final da sua pasta de migrações e substitua o método `up()` de cada um exatamente pelos códigos abaixo:

### 🔹 Tabela de Clientes
PHP

# 

```
public function up(): void{
    Schema::create('clientes', function (Blueprint $table){
        $table->id();
        $table->string('nome');
        $table->string('email')->unique()->nullable();
        $table->string('telefone')->nullable();
        $table->string('documento')->nullable(); // Suporta CPF ou CNPJ
        $table->timestamps();
    });
}
```

### 🔹 Tabela de Fornecedores

PHP

# 

```
public function up(): void{
    Schema::create('fornecedores', function (Blueprint $table){
        $table->id();
        $table->string('nome');
        $table->string('email')->nullable();
        $table->string('telefone')->nullable();
        $table->string('cnpj')->nullable();
        $table->timestamps();
    });
}
```

### 🔹 ### Tabela de Insumos (Matéria-prima fracionada)

PHP

# 

```
public function up(): void{
    Schema::create('insumos', function (Blueprint $table){
        $table->id();
        $table->string('nome'); // Ex: Tecido Jeans, Linha Branca
        $table->string('unidade_medida'); // Ex: Metros, Kg, Cone
        $table->decimal('preco_custo', 10, 2)->nullable();
        $table->decimal('estoque', 10, 2)->default(0); // Suporta frações como 15.45 Kg
        $table->timestamps();
    });
}
```

### 🔹 Tabela de Produtos (Peças acabadas)
PHP

# 

```
public function up(): void{
    Schema::create('produtos', function (Blueprint $table){
        $table->id();
        $table->string('nome'); // Ex: Camiseta Polo Azul M
        $table->string('referencia')->nullable(); // SKU
        $table->decimal('preco_venda', 10, 2)->nullable();
        $table->integer('estoque')->default(0); // Contagem por unidades inteiras
        $table->timestamps();
    });
}
```

### 🔹 Tabela de Pedidos

PHP
```
public function up(): void{
    Schema::create('pedidos', function (Blueprint $table){
        $table->id();
        $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnDelete();
        $table->string('status')->default('Pendente'); // Pendente, Em Produção, Finalizado
        $table->decimal('valor_total', 10, 2)->nullable();
        $table->timestamps();
    });
}
```

### 🔹 Tabela de Itens do Pedido (Relacionamento N:N com atributos)

PHP
```
public function up(): void{
    Schema::create('item_pedidos', function (Blueprint $table){
        $table->id();
        $table->foreignId('pedido_id')->constrained('pedidos')->cascadeOnDelete();
        $table->foreignId('produto_id')->constrained('produtos')->cascadeOnDelete();
        $table->integer('quantidade');
        $table->decimal('preco_unitario', 10, 2);
        $table->timestamps();
    });
}
```

### 🔹 Tabela de Movimentações de Estoque (Histórico de Auditoria)

PHP

```
public function up(): void{
    Schema::create('movimentacoes_estoque', function (Blueprint $table){
        $table->id();
        $table->foreignId('produto_id')->constrained('produtos')->cascadeOnDelete();
        $table->enum('tipo', ['entrada', 'saida']);
        $table->integer('quantidade');
        $table->string('observacao')->nullable(); // Motivo: Compra, Ajuste Manual, Dev.
        $table->timestamps();
    });
}
```

### 2. Configurando a Lógica de Negócios e Relacionamentos nos Models (`app/Models/`)

Abra as classes dentro de `app/Models/` e estruture as atribuições em massa, mapeamentos e gatilhos automatizados de banco de dados.

### 📝 Model `User.php`

Adicione obrigatoriamente a Trait do Spatie para que o Laravel entenda a validação de regras:

PHP

```
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles; // Importação Crucial

class User extends Authenticatable{
    use Notifiable, HasRoles;

    protected $fillable = ['name', 'email', 'password'];
    protected $hidden = ['password', 'remember_token'];
}
```

### 📝 Model `Cliente.php`

PHP

```
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model{
    protected $guarded = []; // Libera preenchimento em lote com segurança tratada no form

    public function pedidos(){
        return $this->hasMany(Pedido::class);
    }
}
```

### 📝 Model `Fornecedor.php`

PHP

#

```
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fornecedor extends Model{
    protected $guarded = [];
}
```

### 📝 Model `Insumo.php`

PHP

```
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Insumo extends Model{
    protected $guarded = [];
}
```

### 📝 Model `Produto.php`

PHP

```
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model{
    protected $guarded = [];

    public function movimentacoes(){
        return $this->hasMany(MovimentacaoEstoque::class);
    }
}
```

### 📝 Model `Pedido.php`

PHP

```
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model{
    protected $guarded = [];

    public function cliente(){
        return $this->belongsTo(Cliente::class);
    }

    public function itens(){
        return $this->hasMany(ItemPedido::class);
    }
}
```

### 📝 Model `ItemPedido.php`

PHP
```
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemPedido extends Model{
    protected $guarded = [];

    public function pedido(){
        return $this->belongsTo(Pedido::class);
    }

    public function produto(){
        return $this->belongsTo(Produto::class);
    }
}
```

### 📝 Model `MovimentacaoEstoque.php` (Gatilho Silencioso do Inventário)

Aqui inserimos o método `booted()` com o ouvinte `created`. Toda vez que uma movimentação for salva em qualquer lugar do sistema, o Laravel acorda e atualiza o saldo real do produto de forma invisível e à prova de falhas:

PHP

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimentacaoEstoque extends Model{
    protected $table = 'movimentacoes_estoque';
    protected $fillable = ['produto_id', 'tipo', 'quantidade', 'observacao'];

    public function produto(){
        return $this->belongsTo(Produto::class);
    }

    protected static function booted(){
        static::created(function ($movimentacao){
            $produto = $movimentacao->produto;

            if ($movimentacao->tipo === 'entrada') {
                $produto->estoque += $movimentacao->quantidade;
            } else {
                $produto->estoque -= $movimentacao->quantidade;
            }

            $produto->save(); // Atualização automática disparada via banco
        });
    }
}

### Executar Migração Estrutural

Finalizada a modelagem conceitual, suba as tabelas em definitivo para o seu servidor:

Bash

# 

```
php artisan migrate
```

## 🎨 MÓDULO 3: Construção das Interfaces com Filament v5

Com a base sólida, gere as estruturas visuais automáticas do painel administrativo via terminal:

Bash

```
php artisan make:filament-resource Cliente
php artisan make:filament-resource Fornecedor
php artisan make:filament-resource Insumo
php artisan make:filament-resource Produto
php artisan make:filament-resource Pedido
php artisan make:filament-resource MovimentacaoEstoque
```

Agora, vamos abrir e editar cada arquivo gerado em `app/Filament/Resources/` para programar o comportamento estrito de cada tela.

### 1. Interface de Clientes (`ClienteResource.php`)

Implementa a máscara JavaScript reativa que lê o tamanho do campo para alternar instantaneamente entre CPF e CNPJ:

PHP
```
namespace App\Filament\Resources;

use App\Filament\Resources\ClienteResource\Pages;
use App\Models\Cliente;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\Resource;
use Filament\Support\RawJs;

class ClienteResource extends Resource{
    protected static ?string $model = Cliente::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form{
        return $form
            ->schema([
                TextInput::make('nome')->required()->label('Nome Completo'),
                TextInput::make('email')->email()->label('E-mail'),
                TextInput::make('telefone')->label('WhatsApp')->mask('(99) 99999-9999'),
                TextInput::make('documento')
                    ->label('CPF ou CNPJ')
                    ->mask(RawJs::make(<<<'JS'
                        $input.length > 14 ? '99.999.999/9999-99' : '999.999.999-99'
                    JS)),
            ]);
    }

    public static function table(Table $table): Table{
        return $table
            ->columns([
                TextColumn::make('nome')->searchable()->sortable(),
                TextColumn::make('email')->searchable(),
                TextColumn::make('telefone'),
                TextColumn::make('documento')->label('Documento Identificador'),
            ]);
    }

    public static function getPages(): array{
        return [
            'index' => Pages\ListClientes::route('/'),
            'create' => Pages\CreateCliente::route('/create'),
            'edit' => Pages\EditCliente::route('/{record}/edit'),
        ];
    }
}
```

### 2. Interface de Fornecedores (`FornecedorResource.php`)

PHP

```
namespace App\Filament\Resources;

use App\Filament\Resources\FornecedorResource\Pages;
use App\Models\Fornecedor;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\Resource;

class FornecedorResource extends Resource{
    protected static ?string $model = Fornecedor::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    public static function form(Form $form): Form{
        return $form
            ->schema([
                TextInput::make('nome')->required()->label('Razão Social / Nome'),
                TextInput::make('email')->email()->label('E-mail'),
                TextInput::make('telefone')->label('Telefone Comercial')->mask('(99) 99999-9999'),
                TextInput::make('cnpj')->label('CNPJ Corporativo')->mask('99.999.999/9999-99'),
            ]);
    }

    public static function table(Table $table): Table{
        return $table
            ->columns([
                TextColumn::make('nome')->searchable()->sortable(),
                TextColumn::make('cnpj')->label('CNPJ'),
                TextColumn::make('telefone'),
            ]);
    }

    public static function getPages(): array{
        return [
            'index' => Pages\ListFornecedores::route('/'),
            'create' => Pages\CreateFornecedor::route('/create'),
            'edit' => Pages\EditFornecedor::route('/{record}/edit'),
        ];
    }
}
```

### 3. Interface de Insumos (`InsumoResource.php`)

PHP

```
namespace App\Filament\Resources;

use App\Filament\Resources\InsumoResource\Pages;
use App\Models\Insumo;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\Resource;

class InsumoResource extends Resource{
    protected static ?string $model = Insumo::class;
    protected static ?string $navigationIcon = 'heroicon-o-scissors';

    public static function form(Form $form): Form{
        return $form
            ->schema([
                TextInput::make('nome')->required()->label('Nome do Insumo'),
                TextInput::make('unidade_medida')->required()->label('Unidade (Ex: Kg, Metros, Cone)'),
                TextInput::make('preco_custo')->numeric()->prefix('R$')->label('Preço de Custo'),
                TextInput::make('estoque')->numeric()->default(0)->label('Estoque Disponível'),
            ]);
    }

    public static function table(Table $table): Table{
        return $table
            ->columns([
                TextColumn::make('nome')->searchable()->sortable(),
                TextColumn::make('unidade_medida')->label('Unid. Medida'),
                TextColumn::make('preco_custo')->money('BRL')->label('Preço de Custo'),
                TextColumn::make('estoque')->label('Saldo Físico'),
            ]);
    }

    public static function getPages(): array{
        return [
            'index' => Pages\ListInsumos::route('/'),
            'create' => Pages\CreateInsumo::route('/create'),
            'edit' => Pages\EditInsumo::route('/{record}/edit'),
        ];
    }
}
```

### 4. Interface de Produtos Acabados (`ProdutoResource.php`)

PHP

```
namespace App\Filament\Resources;

use App\Filament\Resources\ProdutoResource\Pages;
use App\Models\Produto;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\Resource;

class ProdutoResource extends Resource{
    protected static ?string $model = Produto::class;
    protected static ?string $navigationIcon = 'heroicon-o-tag';

    public static function form(Form $form): Form{
        return $form
            ->schema([
                TextInput::make('nome')->required()->label('Nome Comercial do Produto'),
                TextInput::make('referencia')->label('Código de Referência (SKU)'),
                TextInput::make('preco_venda')->numeric()->prefix('R$')->label('Preço de Venda'),
                TextInput::make('estoque')->numeric()->default(0)->integer()->disabled()->label('Estoque Atual (Alterado via Movimentação)'),
            ]);
    }

    public static function table(Table $table): Table{
        return $table
            ->columns([
                TextColumn::make('referencia')->label('Referência/SKU')->searchable(),
                TextColumn::make('nome')->searchable()->sortable(),
                TextColumn::make('preco_venda')->money('BRL')->label('Preço de Venda'),
                TextColumn::make('estoque')->label('Unidades em Estoque'),
            ]);
    }

    public static function getPages(): array{
        return [
            'index' => Pages\ListProdutos::route('/'),
            'create' => Pages\CreateProduto::route('/create'),
            'edit' => Pages\EditProduto::route('/{record}/edit'),
        ];
    }
}
```

### 5. Interface Avançada de Pedidos Comerciais (`PedidoResource.php`)

Estrutura reativa assíncrona baseada em AJAX nativo de fundo. Varre os campos do `Repeater` e injeta a soma matemática no campo `valor_total` (bloqueado como `readOnly` por segurança).

PHP

```
namespace App\Filament\Resources;

use App\Filament\Resources\PedidoResource\Pages;
use App\Models\Pedido;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\Resource;
use Filament\Forms\Get;
use Filament\Forms\Set;

class PedidoResource extends Resource{
    protected static ?string $model = Pedido::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    public static function form(Form $form): Form{
        return $form
            ->schema([
                Select::make('cliente_id')
                    ->relationship('cliente', 'nome')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Cliente Comprador'),

                Select::make('status')
                    ->options([
                        'Pendente' => 'Pendente',
                        'Em Produção' => 'Em Produção',
                        'Finalizado' => 'Finalizado',
                    ])
                    ->default('Pendente')
                    ->required()
                    ->label('Status do Fluxo'),

                TextInput::make('valor_total')
                    ->numeric()
                    ->prefix('R$')
                    ->readOnly()
                    ->label('Valor Total Calculado'),

                Repeater::make('itens')
                    ->relationship('itens')
                    ->schema([
                        Select::make('produto_id')
                            ->relationship('produto', 'nome')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label('Produto Final')
                            ->columnSpan(2),

                        TextInput::make('quantidade')
                            ->numeric()
                            ->default(1)
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Get $get, Set $set) => self::calcularTotal($get, $set))
                            ->columnSpan(1),

                        TextInput::make('preco_unitario')
                            ->numeric()
                            ->prefix('R$')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Get $get, Set $set) => self::calcularTotal($get, $set))
                            ->columnSpan(1),
                    ])
                    ->columns(4)
                    ->columnSpanFull()
                    ->label('Grade de Peças do Pedido')
                    ->live()
                    ->afterStateUpdated(fn (Get $get, Set $set) => self::calcularTotal($get, $set)),
            ]);
    }

    public static function table(Table $table): Table{
        return $table
            ->columns([
                TextColumn::make('id')->label('Ordem #')->sortable(),
                TextColumn::make('cliente.nome')->label('Cliente').searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state){
                        'Pendente' => 'warning',
                        'Em Produção' => 'info',
                        'Finalizado' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('valor_total')->money('BRL')->label('Faturamento Total'),
                TextColumn::make('created_at')->dateTime('d/m/Y H:i')->label('Data de Entrada'),
            ]);
    }

    public static function calcularTotal(Get $get, Set $set): void{
        $itens = $get('itens') ?? [];
        $total = 0;

        foreach ($itens as $item) {
            $quantidade = (float) ($item['quantidade'] ?? 0);
            $preco = (float) ($item['preco_unitario'] ?? 0);
            $total += $quantidade * $preco;
        }

        $set('valor_total', number_format($total, 2, '.', ''));
    }

    public static function getPages(): array{
        return [
            'index' => Pages\ListPedidos::route('/'),
            'create' => Pages\CreatePedido::route('/create'),
            'edit' => Pages\EditPedido::route('/{record}/edit'),
        ];
    }
}
```

### 6. Interface de Extrato Físico de Movimentações (`MovimentacaoEstoqueResource.php`)

PHP

namespace App\Filament\Resources;

use App\Filament\Resources\MovimentacaoEstoqueResource\Pages;
use App\Models\MovimentacaoEstoque;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\Resource;

class MovimentacaoEstoqueResource extends Resource{
    protected static ?string $model = MovimentacaoEstoque::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';

    public static function form(Form $form): Form{
        return $form
            ->schema([
                Select::make('produto_id')
                    ->relationship('produto', 'nome')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Produto Alvo'),

                Select::make('tipo')
                    ->options([
                        'entrada' => 'Entrada (Adicionar Volume)',
                        'saida' => 'Saída (Subtrair Volume)',
                    ])
                    ->required()
                    ->native(false)
                    ->label('Tipo de Fluxo'),

                TextInput::make('quantidade')
                    ->numeric()
                    ->required()
                    ->minValue(1)
                    ->label('Quantidade Movimentada'),

                TextInput::make('observacao')
                    ->maxLength(255)
                    ->placeholder('Ex: Compra de lote de fornecedor, ajuste manual de balanço...')
                    ->columnSpanFull()
                    ->label('Histórico / Detalhes'),
            ]);
    }

    public static function table(Table $table): Table{
        return $table
            ->columns([
                TextColumn::make('created_at')->label('Data/Hora Evento')->dateTime('d/m/Y H:i')->sortable(),
                TextColumn::make('produto.nome')->label('Produto Têxtil')->searchable(),
                TextColumn::make('tipo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state){
                        'entrada' => 'success',
                        'saida' => 'danger',
                    })
                    ->label('Natureza'),
                TextColumn::make('quantidade')->numeric()->label('Qtd Vol.'),
                TextColumn::make('observacao')->label('Observação de Controle'),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array{
        return [
            'index' => Pages\ListMovimentacaoEstoques::route('/'),
            'create' => Pages\CreateMovimentacaoEstoque::route('/create'),
            'edit' => Pages\EditMovimentacaoEstoque::route('/{record}/edit'),
        ];
    }
}

## 🔒 MÓDULO 4: Segurança, Ciclo de Vida & Notificações Assíncronas

Nesta fase ligamos os ganchos do backend para reprocessar cálculos em background, escrever trilhas de logs físicos e disparar notificações corporativas via Mailpit.

### 1. Criando a Classe do E-mail HTML

No seu terminal, gere o arquivo do e-mail de notificação:

Bash

```
php artisan make:mail PedidoCriadoMail
```

Abra o arquivo gerado em `app/Mail/PedidoCriadoMail.php` e altere sua estrutura interna:

PHP

```
namespace App\Mail;

use App\Models\Pedido;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PedidoCriadoMail extends Mailable{
    use Queueable, SerializesModels;

    public $pedido;

    public function __construct(Pedido $pedido){
        $this->pedido = $pedido;
    }

    public function envelope(): Envelope{
        return new Envelope(
            subject: 'Pedido de Confecção Recebido com Sucesso! 🎉',
        );
    }

    public function content(): Content{
        return new Content(
            view: 'emails.pedido-criado',
        );
    }
}
```

Crie o arquivo visual do e-mail em `resources/views/emails/pedido-criado.blade.php`:

HTML

```
<!DOCTYPE html>
<html>
<head><title>Confirmação de Pedido</title></head>
<body style="font-family: sans-serif; color: #333; line-height: 1.6;">
    <h2>Olá, {{ $pedido->cliente->nome }}!</h2>
    <p>Seu pedido foi registrado em nossa planta fabril.</p>
    <p><strong>Código da Ordem:</strong> #{{ $pedido->id }}</p>
    <p><strong>Valor Total das Peças:</strong> R$ {{ number_format($pedido->valor_total, 2, ',', '.') }}</p>
    <p><strong>Status Atual:</strong> {{ $pedido->status }}</p>
    <hr>
    <small>ERP Confecção - Monitoramento de Produção Automatizado</small>
</body>
</html>
```

### 2. Vinculando os Ganchos de Execução no Filament Pages

Abra os arquivos de manipulação do ciclo de vida das páginas do Pedido para forçar a auditoria em arquivo de log físico (`storage/logs/laravel.log`), o recálculo do total e o despacho de e-mails.

### 🏗️ Página de Criação (`app/Filament/Resources/PedidoResource/Pages/CreatePedido.php`)

PHP

```
namespace App\Filament\Resources\PedidoResource\Pages;

use App\Filament\Resources\PedidoResource;
use App\Mail\PedidoCriadoMail;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CreatePedido extends CreateRecord{
    protected static string $resource = PedidoResource::class;

    protected function afterCreate(): void{
        $pedido = $this->record;

        // Recálculo preventivo via Eloquent Collection
        $total = $pedido->itens->sum(fn ($item) => $item->quantidade * $item->preco_unitario);
        $pedido->update(['valor_total' => $total]);

        // Gravação da trilha de auditoria física no servidor
        Log::info('Auditoria: Novo Pedido Comercial Gerado', [
            'pedido_id' => $pedido->id,
            'valor_total' => $total,
            'operador' => auth()->user()->email ?? 'Sistema'
        ]);

        // Disparo assíncrono para o Mailpit
        if ($pedido->cliente && $pedido->cliente->email) {
            Mail::to($pedido->cliente->email)->send(new PedidoCriadoMail($pedido));
        }
    }
}
```

### 🏗️ Página de Edição (`app/Filament/Resources/PedidoResource/Pages/EditPedido.php`)

PHP

namespace App\Filament\Resources\PedidoResource\Pages;

use App\Filament\Resources\PedidoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;

class EditPedido extends EditRecord{
    protected static string $resource = PedidoResource::class;

    protected function getHeaderActions(): array{
        return [Actions\DeleteAction::make()];
    }

    protected function afterSave(): void{
        $pedido = $this->record;
        $total = $pedido->itens->sum(fn ($item) => $item->quantidade * $item->preco_unitario);
        $pedido->update(['valor_total' => $total]);

        Log::info('Auditoria: Pedido Comercial Editado e Atualizado', [
            'pedido_id' => $pedido->id,
            'novo_valor_total' => $total,
            'operador' => auth()->user()->email ?? 'Sistema'
        ]);
    }
}

## 🛡️ MÓDULO 5: Blindagem de Acesso Corporativo (Zero Trust / RBAC)

Por padrão, o Filament deixa as portas abertas. Aplicaremos o conceito **Zero Trust**: o usuário recém-criado nasce sem acesso a absolutamente nada até que receba um cartão magnético (Cargo) com permissões expressas.

### 1. Injetando a Chave Mestra para o Administrador Geral

Abra `app/Providers/AppServiceProvider.php` e ajuste o método `boot()` para criar um interceptador global. Se o usuário tiver o cargo estrito de `Admin`, ele ignora qualquer verificação e ganha passe livre:

PHP

```
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider{
    public function register(): void{}

    public function boot(): void{
        // O Super Admin ignora as amarras do sistema de travas
        Gate::before(function ($user, $ability){
            return $user->hasRole('Admin') ? true : null;
        });
    }
}
```

### 2. Gerando os Recursos Visuais de Governança

Execute no terminal os comandos para gerenciar os cargos, as permissões e o quadro de funcionários:

Bash

```
php artisan make:filament-resource Role
php artisan make:filament-resource Permission
php artisan make:filament-resource User --generate
```

### 3. Configurando a Interface de Permissões (`PermissionResource.php`)

PHP

```
namespace App\Filament\Resources;

use App\Filament\Resources\PermissionResource\Pages;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\Resource;
use Spatie\Permission\Models\Permission; // Model Oficial do Pacote Spatie

class PermissionResource extends Resource{
    protected static ?string $model = Permission::class;
    protected static ?string $navigationIcon = 'heroicon-o-key';

    public static function form(Form $form): Form{
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Identificador da Permissão (Ex: acessar_clientes)')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table{
        return $table
            ->columns([
                TextColumn::make('name')->label('Permissão')->searchable(),
                TextColumn::make('created_at')->label('Data de Cadastro').dateTime('d/m/Y'),
            ]);
    }

    public static function canAccess(): bool{
        return auth()->user()?->hasRole('Admin') ?? false; // Tranca Absoluta da Porta
    }

    public static function getPages(): array{
        return [
            'index' => Pages\ListPermissions::route('/'),
            'create' => Pages\CreatePermission::route('/create'),
            'edit' => Pages\EditPermission::route('/{record}/edit'),
        ];
    }
}
```

### 4. Configurando a Interface de Cargos (`RoleResource.php`)

PHP
```
namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\Resource;
use Spatie\Permission\Models\Role; // Model Oficial do Pacote Spatie

class RoleResource extends Resource{
    protected static ?string $model = Role::class;
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    public static function form(Form $form): Form{
        return $form
            ->schema([
                TextInput::make('name')->label('Nome do Cargo (Ex: Gerente Comercial)')->required()->unique(ignoreRecord: true),
                Select::make('permissions')
                    ->label('Permissões Atribuídas ao Cargo')
                    ->multiple()
                    ->relationship('permissions', 'name')
                    ->preload()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table{
        return $table
            ->columns([
                TextColumn::make('name')->label('Cargo Industrial')->searchable(),
                TextColumn::make('created_at')->label('Instalado em')->dateTime('d/m/Y'),
            ]);
    }

    public static function canAccess(): bool{
        return auth()->user()?->hasRole('Admin') ?? false;
    }

    public static function getPages(): array{
        return [
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
```

### 5. Configurando o Gerenciamento da Equipe (`UserResource.php`)

PHP

```
namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\Resource;

class UserResource extends Resource{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form{
        return $form
            ->schema([
                TextInput::make('name')->required()->label('Nome do Funcionário'),
                TextInput::make('email')->email()->required()->label('E-mail de Login'),
                TextInput::make('password')
                    ->password()
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create')
                    ->label('Senha de Acesso'),
                Select::make('roles')
                    ->label('Cargo de Atuação')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table{
        return $table
            ->columns([
                TextColumn::make('name')->label('Funcionário')->searchable(),
                TextColumn::make('email')->label('E-mail corporativo'),
                TextColumn::make('roles.name')->badge()->label('Cargos Ativos'),
            ]);
    }

    public static function canAccess(): bool{
        return auth()->user()?->hasRole('Admin') ?? false;
    }

    public static function getPages(): array{
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
```

### 6. Instalando as Fechaduras nas Portas de Negócio

Para concluir o ecossistema de segurança baseada na Metáfora do Hotel, abra os arquivos comerciais restantes e force o método `canAccess()` a ler as permissões do Spatie:

PHP

// Adicione dentro do ClienteResource.php
public static function canAccess(): bool{
    return auth()->user()?->can('acessar_clientes') ?? false;
}

// Adicione dentro do FornecedorResource.php
public static function canAccess(): bool{
    return auth()->user()?->can('acessar_fornecedores') ?? false;
}

// Adicione dentro do InsumoResource.php
public static function canAccess(): bool{
    return auth()->user()?->can('acessar_insumos') ?? false;
}

// Adicione dentro do ProdutoResource.php
public static function canAccess(): bool{
    return auth()->user()?->can('acessar_produtos') ?? false;
}

// Adicione dentro do PedidoResource.php
public static function canAccess(): bool{
    return auth()->user()?->can('acessar_pedidos') ?? false;
}

// Adicione dentro do MovimentacaoEstoqueResource.php
public static function canAccess(): bool{
    return auth()->user()?->can('acessar_movimentacoes') ?? false;
}

## 🧰 MÓDULO 6: Caixa de Ferramentas e Sobrevivência do Sistema

O ecossistema trabalha com cache pesado para otimizar a velocidade de resposta do servidor. Sempre que alterar configurações estruturais ou criar novos cargos/permissões, force o recarregamento total executando o comando abaixo na raiz do projeto:

Bash

# 

```
php artisan optimize:clear
```
