<?php

namespace App\Filament\Resources\Insumos;

use App\Filament\Resources\Insumos\Pages\CreateInsumo;
use App\Filament\Resources\Insumos\Pages\EditInsumo;
use App\Filament\Resources\Insumos\Pages\ListInsumos;
use App\Filament\Resources\Insumos\Pages\ViewInsumo;
use App\Filament\Resources\Insumos\Schemas\InsumoForm;
use App\Filament\Resources\Insumos\Schemas\InsumoInfolist;
use App\Filament\Resources\Insumos\Tables\InsumosTable;
use App\Models\Insumo;
use App\Rules\MoneyValidation;
use App\Rules\PositiveMoneyValidation;
use App\Support\BrazilianFormat;
use BackedEnum;
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

class InsumoResource extends Resource
{
    protected static ?string $model = Insumo::class;

    protected static string|UnitEnum|null $navigationGroup = 'Estoque';
    protected static ?int $navigationSort = 1;

    public static function canAccess(): bool {
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
        InsumoForm::configure($schema);

        return $schema->components([
            TextInput::make('nome')
                ->label('Nome do Insumo')
                ->required()
                ->maxLength(255)
                ->unique(ignoreRecord: true)
                ->validationMessages([
                    'required' => 'Informe o nome do insumo.',
                    'unique' => 'Este insumo ja esta cadastrado.',
                ]),

            Select::make('unidade_medida')
                ->label('Un. de Medida')
                ->options([
                    'unidade'    => 'un',
                    'metro'      => 'm',
                    'centimetro' => 'cm',
                    'milimetro'  => 'mm',
                ])
                ->searchable()
                ->required()
                ->validationMessages(['required' => 'Selecione a unidade de medida.']),

            TextInput::make('preco_custo')
                ->label('Preço de Custo')
                ->prefix('R$')
                ->required()
                ->rules([
                    'required',
                    new MoneyValidation(),
                    new PositiveMoneyValidation(),
                ])
                ->validationMessages([
                    'required' => 'Informe o preço de custo.',
                ])
                ->extraInputAttributes(['type' => 'text']) 
                ->mask(RawJs::make(<<<'JS'
                    $money($input, ',', '.', 2)
                JS))
                ->numeric(false) 
                ->formatStateUsing(fn ($state) => self::formatCurrency($state))
                ->dehydrateStateUsing(fn ($state) => self::parseCurrency($state))
                ->helperText('Informe o valor no formato brasileiro. Ex: 1.500,00'),

            TextInput::make('estoque')
                ->label('Estoque Atual')
                ->required()
                ->rules(['required', 'numeric', 'min:0'])
                ->validationMessages([
                    'required' => 'Informe o estoque atual.',
                    'numeric' => 'Informe uma quantidade válida.',
                    'min' => 'O estoque não pode ser negativo.',
                ])
                // Máscara para permitir apenas números inteiros ou decimais simples
                ->mask(RawJs::make(<<<'JS'
                    $input.replace(/[^0-9.]/g, '')
                JS)),
        ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return InsumoInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        InsumosTable::configure($table);
        return $table ->
        columns([
            TextColumn::make('nome')->label('Nome do Insumo')->searchable(),
            TextColumn::make('unidade_medida')
                ->label('Un. de Medida')
                ->searchable()
                ->formatStateUsing(fn (?string $state): string => match ($state) {
                    'unidade' => 'un',
                    'metro' => 'm',
                    'centimetro' => 'cm',
                    'milimetro' => 'mm',
                    default => $state ?? '-',
                }),
            TextColumn::make('preco_custo')->label('Preço/Custo')
                ->formatStateUsing(fn ($state) => BrazilianFormat::currency($state)),
            TextColumn::make('estoque')->searchable(),
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
            'index' => ListInsumos::route('/'),
            'create' => CreateInsumo::route('/create'),
            'view' => ViewInsumo::route('/{record}'),
            'edit' => EditInsumo::route('/{record}/edit'),
        ];
    }
}
