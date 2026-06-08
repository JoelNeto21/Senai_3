<?php

namespace App\Filament\Resources\Pedidos;

use App\Filament\Resources\Pedidos\Pages\CreatePedido;
use App\Filament\Resources\Pedidos\Pages\EditPedido;
use App\Filament\Resources\Pedidos\Pages\ListPedidos;
use App\Filament\Resources\Pedidos\Pages\ViewPedido;
use App\Filament\Resources\Pedidos\Schemas\PedidoForm;
use App\Filament\Resources\Pedidos\Schemas\PedidoInfolist;
use App\Filament\Resources\Pedidos\Tables\PedidosTable;
use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\Produto;
use App\Rules\MoneyValidation;
use App\Rules\PositiveMoneyValidation;
use App\Support\BrazilianFormat;
use BackedEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Schemas\Components\Utilities\Get;

use UnitEnum;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Support\RawJs;
use Filament\Schemas\Components\Utilities\Set;

class PedidoResource extends Resource
{
    protected static ?string $model = Pedido::class;

    protected static string|UnitEnum|null $navigationGroup = 'Vendas';
    protected static ?int $navigationSort = 1;

    public static function canAccess(): bool {
        return auth()->user()?->hasAnyRole(['Admin', 'Cliente']) ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->hasAnyRole(['Admin', 'Cliente']) ?? false;
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()?->hasRole('Admin') ?? false;
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()?->hasRole('Admin') ?? false;
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()?->hasRole('Admin') ?? false;
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with(['cliente', 'itens.produto']);

        if (auth()->user()?->hasRole('Cliente')) {
            $query->whereHas('cliente', fn (Builder $query) => $query->where('email', auth()->user()?->email));
        }

        return $query;
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Pedido';

    public static function form(Schema $schema): Schema
    {
        PedidoForm::configure($schema);

        return $schema->components([
            Select::make('cliente_id')
                ->relationship('cliente', 'nome')
                ->preload()
                ->searchable()
                ->default(fn () => self::getAuthenticatedClienteId())
                ->disabled(fn () => auth()->user()?->hasRole('Cliente') ?? false)
                ->dehydrated()
                ->required()
                ->label('Selecione o Cliente')
                ->validationMessages([
                    'required' => 'Selecione um cliente.',
                ]),

            Select::make('status')
                ->options([
                    'Pendente' => 'Pendente',
                    'Em Produção' => 'Em Produção',
                    'Entregue' => 'Entregue',
                ])
                ->default('Pendente')
                ->disabled(fn () => auth()->user()?->hasRole('Cliente') ?? false)
                ->dehydrated()
                ->searchable()
                ->required()
                ->validationMessages([
                    'required' => 'Selecione o status do pedido.',
                ]),

            TextInput::make('valor_total')
                ->label('Valor Total do Pedido')
                ->prefix('R$')
                ->rules(['required', new MoneyValidation()])
                ->required()
                ->readonly() // Geralmente o total é calculado, então deixamos apenas leitura
                ->mask(RawJs::make(<<<'JS'
                    $money($input, ',', '.', 2)
                JS))
                ->formatStateUsing(fn ($state) => self::formatCurrency($state))
                ->dehydrateStateUsing(fn ($state) => self::parseCurrency($state)),

            Repeater::make('itens')
                ->relationship('itens')
                ->schema([
                    Select::make('produto_id')
                        ->relationship('produto', 'nome')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->label('Selecione o Produto')
                        ->validationMessages([
                            'required' => 'Selecione um produto.',
                        ])
                        ->live()
                        ->afterStateUpdated(function (mixed $state, Get $get, Set $set): void {
                            $price = Produto::query()->whereKey($state)->value('valor_unitario');

                            if ($price !== null) {
                                $set('preco_un', BrazilianFormat::currencyInput($price));
                            }

                            self::calcularTotal($get, $set);
                        })
                        ->columnSpan(2),

                    TextInput::make('quantidade')
                        ->default(1)
                        ->required()
                        ->rules(['required', 'integer', 'min:1'])
                        ->validationMessages([
                            'required' => 'Informe a quantidade.',
                            'integer' => 'A quantidade deve ser um número inteiro.',
                            'min' => 'A quantidade deve ser maior que zero.',
                        ])
                        ->mask(RawJs::make(<<<'JS'
                            $input.replace(/[^0-9]/g, '')
                        JS))
                        ->live(debounce: 300)
                        ->afterStateUpdated(fn (Get $get, Set $set) => self::calcularTotal($get, $set))
                        ->dehydrateStateUsing(fn ($state) => (int) preg_replace('/\D/', '', (string) $state))
                        ->columnSpan(1),

                    TextInput::make('preco_un')
                        ->label('Preço Unitário')
                        ->prefix('R$')
                        ->readonly(fn () => auth()->user()?->hasRole('Cliente') ?? false)
                        ->required()
                        ->rules([
                            'required',
                            new MoneyValidation(),
                            new PositiveMoneyValidation(),
                        ])
                        ->validationMessages([
                            'required' => 'Informe o preço unitário.',
                        ])
                        ->extraInputAttributes(['type' => 'text']) 
                        ->mask(RawJs::make(<<<'JS'
                            $money($input, ',', '.', 2)
                        JS))
                        ->numeric(false)
                        ->formatStateUsing(fn ($state) => self::formatCurrency($state))
                        ->dehydrateStateUsing(fn ($state) => self::parseCurrency($state))
                        ->live(debounce: 300)
                        ->afterStateUpdated(fn (Get $get, Set $set) => self::calcularTotal($get, $set))
                        ->columnSpan(1),
                ])
                ->columnSpanFull()
                ->label('Produtos do Pedido')
                ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
                    if (auth()->user()?->hasRole('Cliente')) {
                        $data['preco_un'] = Produto::query()
                            ->whereKey($data['produto_id'] ?? null)
                            ->value('valor_unitario') ?? 0;
                    }

                    return $data;
                })
                ->live(debounce: 300)
                ->afterStateUpdated(fn (Get $get, Set $set) => self::calcularTotal($get, $set)),
        ]);
    }

    /**
     * Auxiliar para converter string da máscara em float
     */
    protected static function parseCurrency($value): float
    {
        return BrazilianFormat::decimal($value);
    }

    protected static function formatCurrency($value): string
    {
        return BrazilianFormat::currencyInput($value);
    }

    protected static function getAuthenticatedClienteId(): ?int
    {
        return Cliente::query()
            ->where('email', auth()->user()?->email)
            ->value('id');
    }

    public static function infolist(Schema $schema): Schema
    {
        return PedidoInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        PedidosTable::configure($table);
        return $table
            ->columns([
                TextColumn::make('cliente.nome')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state):string=>match($state){
                        'Pendente' => 'warning',
                        'Em Produção'   => 'info',
                        'Entregue'   => 'success',
                        default => 'gray',
                    }),

                TextColumn::make('valor_total')
                    ->label('Valor Total')
                    ->formatStateUsing(fn ($state) => BrazilianFormat::currency($state))
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Data do Pedido')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPedidos::route('/'),
            'create' => CreatePedido::route('/create'),
            'view' => ViewPedido::route('/{record}'),
            'edit' => EditPedido::route('/{record}/edit'),
        ];
    }

    public static function calcularTotal(Get $get, Set $set): void
    {
        $itens = $get('itens') ?? [];
        $total = 0;

        foreach($itens as $item){
            $quantidade = isset($item['quantidade']) ? (int) preg_replace('/\D/', '', (string) $item['quantidade']) : 0;
            $preco = isset($item['preco_un']) ? self::parseCurrency($item['preco_un']) : 0;
            $total += $quantidade * $preco;
        }

        $set('valor_total', number_format($total, 2, ',', '.'));
    }
}
