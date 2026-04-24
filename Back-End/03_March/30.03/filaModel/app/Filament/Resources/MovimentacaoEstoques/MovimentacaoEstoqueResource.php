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
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Support\RawJs;

class MovimentacaoEstoqueResource extends Resource
{
    protected static ?string $model = MovimentacaoEstoque::class;

    public static function canAccess(): bool {
        return auth()->user()?->hasRole('Admin') ?? false;
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'MovimentacaoEstoque';

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
                ->columnSpan(2),
            
            Select::make('movimentacao')
                ->options([
                    'Entrada' => 'Entrada',
                    'Saída'   => 'Saída',
                ])
                ->label('Tipo de Movimentação')
                ->default('Saída')
                ->searchable()
                ->required(),
                
            TextInput::make('quantidade')
                ->label('Quantidade')
                ->required()
                // Máscara que aceita números e ponto/vírgula para decimais
                ->mask(RawJs::make(<<<'JS'
                    $input.replace(/[^0-9.,]/g, '')
                JS))
                // Padroniza para salvar como decimal no banco (troca vírgula por ponto)
                ->dehydrateStateUsing(fn ($state) => (float) str_replace(',', '.', $state)),

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
            'index' => ListMovimentacaoEstoques::route('/'),
            'create' => CreateMovimentacaoEstoque::route('/create'),
            'view' => ViewMovimentacaoEstoque::route('/{record}'),
            'edit' => EditMovimentacaoEstoque::route('/{record}/edit'),
        ];
    }
}
