<?php

namespace App\Filament\Resources\MovimentacaoEstoques;

use App\Filament\Resources\MovimentacaoEstoques\Pages\CreateMovimentacaoEstoque;
use App\Filament\Resources\MovimentacaoEstoques\Pages\EditMovimentacaoEstoque;
use App\Filament\Resources\MovimentacaoEstoques\Pages\ListMovimentacaoEstoques;
use App\Filament\Resources\MovimentacaoEstoques\Pages\ViewMovimentacaoEstoque;
use App\Filament\Resources\MovimentacaoEstoques\Schemas\MovimentacaoEstoqueForm;
use App\Filament\Resources\MovimentacaoEstoques\Schemas\MovimentacaoEstoqueInfolist;
use App\Filament\Resources\MovimentacaoEstoques\Tables\MovimentacaoEstoquesTable;
use App\Models\MovimentacaoEstoque;
use BackedEnum;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Schemas\Components\Utilities\Get;

use UnitEnum;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Support\RawJs;

class MovimentacaoEstoqueResource extends Resource
{
    protected static ?string $model = MovimentacaoEstoque::class;

    protected static string|UnitEnum|null $navigationGroup = 'Estoque';
    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Movimentações de Estoque';
    protected static ?string $modelLabel = 'Movimentação de Estoque';
    protected static ?string $pluralModelLabel = 'Movimentações de Estoque';

    public static function canAccess(): bool {
        return auth()->user()?->hasRole('Admin') ?? false;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('produto');
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        MovimentacaoEstoqueForm::configure($schema);

        return $schema->components([
            Select::make('produto_id')
                ->relationship('produto', 'nome')
                ->searchable()
                ->preload()
                ->required()
                ->label('Selecione o Produto')
                ->live()
                ->validationMessages([
                    'required' => 'Selecione um produto.',
                ])
                ->columnSpan(2),
            
            Select::make('movimentacao')
                ->options([
                    'Entrada' => 'Entrada',
                    'Saída'   => 'Saída',
                ])
                ->label('Tipo de Movimentação')
                ->default('Saída')
                ->searchable()
                ->required()
                ->live()
                ->validationMessages([
                    'required' => 'Selecione o tipo de movimentação.',
                ]),
                
            TextInput::make('quantidade')
                ->label('Quantidade')
                ->required()
                ->rules(['required', 'integer', 'min:1'])
                ->validationMessages([
                    'required' => 'Informe a quantidade.',
                    'integer' => 'A quantidade deve ser um número inteiro.',
                    'min' => 'A quantidade deve ser maior que zero.',
                ])
                ->live(onBlur: true)
                ->afterStateUpdated(function (Get $get, $state, $set) {
                    $produtoId = $get('produto_id');
                    $tipo = $get('movimentacao');
                    
                    if (! $produtoId || ! $tipo || ! $state) {
                        return;
                    }
                    
                    if (in_array($tipo, ['Saída', 'Saida', 'SaÃ­da'], true)) {
                        $produto = \App\Models\Produto::find($produtoId);
                        if ($produto && (int) $state > (int) $produto->quantidade) {
                            $error = 'Quantidade solicitada maior que o estoque disponível (estoque atual: ' . $produto->quantidade . ').';
                            $set('quantidade_error', $error);
                        } else {
                            $set('quantidade_error', null);
                        }
                    } else {
                        $set('quantidade_error', null);
                    }
                })
                ->extraAttributes(fn (Get $get) => [
                    'class' => $get('quantidade_error') ? 'border-red-500 ring-red-500' : '',
                ])
                ->hint(fn (Get $get) => $get('quantidade_error'))
                ->hintColor('danger')
                // Máscara que aceita apenas números inteiros
                ->mask(RawJs::make(<<<'JS'
                    $input.replace(/[^0-9]/g, '')
                JS))
                // Padroniza para salvar como inteiro no banco
                ->dehydrateStateUsing(fn ($state) => (int) preg_replace('/\D/', '', (string) $state))
                ->helperText(function (Get $get) {
                    $produtoId = $get('produto_id');
                    $tipo = $get('movimentacao');
                    
                    if ($produtoId && in_array($tipo, ['Saída', 'Saida', 'SaÃ­da'], true)) {
                        $produto = \App\Models\Produto::find($produtoId);
                        if ($produto) {
                            return 'Estoque disponível: ' . $produto->quantidade;
                        }
                    }
                    return null;
                }),

            TextInput::make('descricao')
                ->label('Descrição/Motivo')
                ->maxLength(255),
        ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return MovimentacaoEstoqueInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        MovimentacaoEstoquesTable::configure($table);
        return $table
            ->columns([
                TextColumn::make('produto.nome')
                    ->label('Produto')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('descricao')
                ->label('Descrição'),
                    
                TextColumn::make('quantidade')
                ->sortable(),
                                
                TextColumn::make('movimentacao')
                    ->label('Movimentação')
                    ->badge()
                    ->color(fn (string $state):string=>match($state){
                        'Entrada'   => 'success',
                        'Saída' => 'warning',
                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label('Data da Movimentação')
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
            'index' => ListMovimentacaoEstoques::route('/'),
            'create' => CreateMovimentacaoEstoque::route('/create'),
            'view' => ViewMovimentacaoEstoque::route('/{record}'),
            'edit' => EditMovimentacaoEstoque::route('/{record}/edit'),
        ];
    }
}
