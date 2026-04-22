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

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Insumo';

    public static function form(Schema $schema): Schema
    {
        InsumoForm::configure($schema);

        return $schema->components([
            TextInput::make('nome')
                ->label('Nome do Insumo')
                ->required(),

            Select::make('unidade_medida')
                ->label('Un. de Medida')
                ->options([
                    'unidade'    => 'un',
                    'metro'      => 'm',
                    'centimetro' => 'cm',
                    'milimetro'  => 'mm',
                ])
                ->searchable()
                ->required(),

            TextInput::make('preco_custo')
                ->label('Preço/Custo')
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
                ->dehydrateStateUsing(fn ($state) => $state ? (float) str_replace(['.', ','], ['', '.'], $state) : 0),

            TextInput::make('estoque')
                ->label('Estoque Atual')
                ->required()
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
