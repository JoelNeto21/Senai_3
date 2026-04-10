<?php

namespace App\Filament\Resources\Pedidos;

use App\Filament\Resources\Pedidos\Pages\CreatePedido;
use App\Filament\Resources\Pedidos\Pages\EditPedido;
use App\Filament\Resources\Pedidos\Pages\ListPedidos;
use App\Filament\Resources\Pedidos\Pages\ViewPedido;
use App\Filament\Resources\Pedidos\Schemas\PedidoForm;
use App\Filament\Resources\Pedidos\Schemas\PedidoInfolist;
use App\Filament\Resources\Pedidos\Tables\PedidosTable;
use App\Models\Pedido;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Schemas\Components\Utilities\GET;
use Filament\Schemas\Components\Utilities\SET;
use Filament\Support\RawJs;

class PedidoResource extends Resource
{
    protected static ?string $model = Pedido::class;

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
                ->required()
                ->label('Selecione o Cliente'),

            Select::make('status')
                ->options([
                    'Pendente' => 'Pendente',
                    'Em Produção' => 'Em Produção',
                    'Entregue' => 'Entregue',
                ])
                ->default('Pendente')
                ->searchable()
                ->required(),

            TextInput::make('valor_total')
                ->label('Valor Total do Pedido')
                ->prefix('R$')
                ->required()
                ->readonly() // Geralmente o total é calculado, então deixamos apenas leitura
                ->mask(RawJs::make(<<<'JS'
                    $money($input, ',', '.', 2)
                JS))
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
                        ->columnSpan(2),

                    TextInput::make('quantidade')
                        ->default(1)
                        ->required()
                        ->mask(RawJs::make(<<<'JS'
                            $input.replace(/[^0-9.,]/g, '')
                        JS))
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (Get $get, Set $set) => self::calcularTotal($get, $set))
                        ->columnSpan(1),

                    TextInput::make('preco_un')
                        ->label('Preço Unitário')
                        ->prefix('R$')
                        ->required()
                        ->extraInputAttributes(['type' => 'text']) 
                        ->mask(RawJs::make(<<<'JS'
                            $money($input, ',', '.', 2)
                        JS))
                        // Importante: informa ao Filament para não converter para número automaticamente no front-end
                        ->numeric(false) 
                        // Garante a exibição correta ao carregar
                        ->formatStateUsing(fn ($state) => $state ? number_format((float) $state, 2, ',', '.') : null)
                        // Converte de volta para decimal antes de salvar
                        ->dehydrateStateUsing(fn ($state) => $state ? (float) str_replace(['.', ','], ['', '.'], $state) : 0)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (Get $get, Set $set) => self::calcularTotal($get, $set))
                        ->columnSpan(1),
                ])
                ->columnSpanFull()
                ->label('Produtos do Pedido')
                ->live()
                ->afterStateUpdated(fn (Get $get, Set $set) => self::calcularTotal($get, $set)),
        ]);
    }

    /**
     * Auxiliar para converter string da máscara em float
     */
    protected static function parseCurrency($value): float
    {
        if (!$value) return 0;
        return (float) str_replace(['.', ','], ['', '.'], $value);
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
                    ->money('BRL')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Data do Pedido')
                    ->datetime('d/m/y H:i')
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
            $quantidade = (float) ($item['quantidade'] ?? 0);
            $preco = (float) ($item['preco_un'] ?? 0);
            $total += $quantidade * $preco;
        }

        $set('valor_total', number_format($total, 2, '.', ''));
    }
}
