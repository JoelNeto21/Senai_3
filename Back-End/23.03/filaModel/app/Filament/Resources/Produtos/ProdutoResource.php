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

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;

class ProdutoResource extends Resource
{
    protected static ?string $model = Produto::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Produto';

    public static function form(Schema $schema): Schema
    {
        ProdutoForm::configure($schema);
        return $schema ->
        components([
            TextInput::make('nome')->required()->label('Nome do Produto'),
            Select::make('categoria')
                ->options([
                    'eletronicos' => 'Eletrônicos',
                    'vestuario'   => 'Vestuário',
                    'alimentos'   => 'Alimentos',
                    'servicos'    => 'Serviços',
                ])
                ->searchable()
                ->required(),
            TextInput::make('descricao')->label('Descrição'),
            TextInput::make('valor_total')->numeric()->prefix('R$')->required(),
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
