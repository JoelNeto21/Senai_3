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
        if ($value === null || $value === '') {
            return '0,00';
        }

        if (is_numeric($value)) {
            return number_format((float) $value, 2, ',', '.');
        }

        // Remove R$ prefix and spaces
        $normalized = preg_replace('/[R$\s]/', '', (string) $value);
        // Remove thousands separator
        $normalized = str_replace('.', '', $normalized);
        // Replace decimal comma with dot
        $normalized = str_replace(',', '.', $normalized);

        if (is_numeric($normalized)) {
            return number_format((float) $normalized, 2, ',', '.');
        }

        return '0,00';
    }

    protected static function parseCurrency($value): float
    {
        if ($value === null || $value === '') {
            return 0.0;
        }

        if (is_numeric($value)) {
            return (float) $value;
        }

        // Remove R$ prefix and spaces
        $normalized = preg_replace('/[R$\s]/', '', (string) $value);
        // Remove thousands separator
        $normalized = str_replace('.', '', $normalized);
        // Replace decimal comma with dot
        $normalized = str_replace(',', '.', $normalized);

        return (float) $normalized;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Insumo';

    public static function form(Schema $schema): Schema
    {
        InsumoForm::configure($schema);

        return $schema->components([
            TextInput::make('nome')
                ->label('Nome do Insumo')
                ->required()
                ->validationMessages(['required' => 'Informe o nome do insumo.']),

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
                ->rules(['required'])
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
            TextColumn::make('unidade_medida')->label('Un. de Medida')->searchable(),
            TextColumn::make('preco_custo')->label('Preço/Custo')->money('BRL'),
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
