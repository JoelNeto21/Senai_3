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
use App\Rules\MoneyValidation;
use App\Rules\PositiveMoneyValidation;
use App\Support\BrazilianFormat;
use BackedEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
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
        return auth()->user()?->hasAnyRole(['Admin', 'Cliente']) ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->hasRole('Admin') ?? false;
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

    protected static function formatCurrency($value): string
    {
        return BrazilianFormat::currencyInput($value);
    }

    protected static function parseCurrency($value): float
    {
        return BrazilianFormat::decimal($value);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nome';

    public static function form(Schema $schema): Schema
    {
        ProdutoForm::configure($schema);

        return $schema->components([
            TextInput::make('nome')
                ->label('Nome do Produto')
                ->required()
                ->unique(ignoreRecord: true)
                ->validationMessages([
                    'required' => 'Informe o nome do produto.',
                    'unique' => 'Este produto ja esta cadastrado.',
                ])
                ->maxLength(255),

            Select::make('categoria')
                ->label('Categoria')
                ->options([
                    'eletronicos' => 'Eletrônicos',
                    'vestuario'   => 'Vestuário',
                    'alimentos'   => 'Alimentos',
                ])
                ->searchable()
                ->required()
                ->validationMessages(['required' => 'Selecione a categoria.']),

            TextInput::make('descricao')
                ->label('Descrição')
                ->maxLength(255),

            TextInput::make('valor_unitario')
                ->label('Preço Unitário')
                ->prefix('R$')
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
                ->helperText('Informe o valor no formato brasileiro. Ex: 1.500,00'),

            TextInput::make('quantidade')
                ->label('Quantidade em Estoque')
                ->required()
                ->rules(['required', 'integer', 'min:0'])
                ->validationMessages([
                    'required' => 'Informe a quantidade em estoque.',
                    'integer' => 'A quantidade deve ser um número inteiro.',
                    'min' => 'A quantidade não pode ser negativa.',
                ])
                ->default(0)
                ->mask(RawJs::make(<<<'JS'
                    $input.replace(/[^0-9]/g, '')
                JS)),
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
            TextColumn::make('categoria')
                ->label('Categoria')
                ->searchable()
                ->formatStateUsing(fn (?string $state): string => match ($state) {
                    'eletronicos' => 'Eletrônicos',
                    'vestuario' => 'Vestuário',
                    'alimentos' => 'Alimentos',
                    default => $state ?? '-',
                }),
            TextColumn::make('descricao')->label('Descrição'),
            TextColumn::make('valor_unitario')->label('Preço Unitário')
                ->formatStateUsing(fn ($state) => BrazilianFormat::currency($state)),
            TextColumn::make('quantidade')
                ->label('Quantidade')
                ->sortable()
                ->badge()
                ->color(fn (int $state): string => $state <= 5 ? 'danger' : 'gray')
                ->formatStateUsing(fn ($state) => (string) $state),
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
