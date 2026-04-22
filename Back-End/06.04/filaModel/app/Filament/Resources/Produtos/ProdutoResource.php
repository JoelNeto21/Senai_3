<?php

namespace App\Filament\Resources\Produtos;

use App\Filament\Resources\Produtos\Pages\CreateProduto;
use App\Filament\Resources\Produtos\Pages\EditProduto;
use App\Filament\Resources\Produtos\Pages\ListProdutos;
use App\Filament\Resources\Produtos\Pages\ViewProduto;
use App\Filament\Resources\Produtos\Schemas\ProdutoForm;
use App\Filament\Resources\Produtos\Schemas\ProdutoInfolist;
use App\Filament\Resources\Produtos\Tables\ProdutosTable;
use App\Models\Produto;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

use UnitEnum;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Support\RawJs;

class ProdutoResource extends Resource
{
    protected static ?string $model = Produto::class;

    protected static string|UnitEnum|null $navigationGroup = 'Estoque';
    protected static ?int $navigationSort = 2;
    
    public static function canAccess(): bool {
        return auth()->user()?->hasRole('Admin') ?? false;
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Produto';

    public static function form(Schema $schema): Schema
    {
        ProdutoForm::configure($schema);

        return $schema->components([
            TextInput::make('nome')
                ->label('Nome do Produto')
                ->required()
                ->maxLength(255),

            Select::make('categoria')
                ->label('Categoria')
                ->options([
                    'eletronicos' => 'Eletrônicos',
                    'vestuario'   => 'Vestuário',
                    'alimentos'   => 'Alimentos',
                ])
                ->searchable()
                ->required(),

            TextInput::make('descricao')
                ->label('Descrição')
                ->maxLength(255),

            TextInput::make('valor_total')
                ->label('Preço de Venda')
                ->prefix('R$')
                ->required()
                // Aplica a máscara monetária brasileira (ex: 1.250,00)
                ->extraInputAttributes(['type' => 'text']) 
                ->mask(RawJs::make(<<<'JS'
                    $money($input, ',', '.', 2)
                JS))
                // Importante: informa ao Filament para não converter para número automaticamente no front-end
                ->numeric(false) 
                // Garante a exibição correta ao carregar
                ->formatStateUsing(fn ($state) => $state ? number_format((float) $state, 2, ',', '.') : null)
                // Converte de volta para decimal antes de salvar
                ->dehydrateStateUsing(fn ($state) => $state ? (float) str_replace(['.', ','], ['', '.'], $state) : 0),
        ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProdutoInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        ProdutosTable::configure($table);
        return $table ->
        columns([
            TextColumn::make('nome')->label('Nome do Produto')->searchable(),
            TextColumn::make('categoria')->searchable(),
            TextColumn::make('descricao')->label('Descrição'),
            TextColumn::make('valor_total')->searchable()->money('BRL'),
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
            'index' => ListProdutos::route('/'),
            'create' => CreateProduto::route('/create'),
            'view' => ViewProduto::route('/{record}'),
            'edit' => EditProduto::route('/{record}/edit'),
        ];
    }
}
