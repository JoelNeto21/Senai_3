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

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;

class InsumoResource extends Resource
{
    protected static ?string $model = Insumo::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Insumo';

    public static function form(Schema $schema): Schema
    {
        InsumoForm::configure($schema);
        return $schema ->
        components([
            TextInput::make('nome')->label('Nome do Insumo')->required(),
            Select::make('unidade_medida')->label('Un. de Medida')
                ->options([
                    'unidade'   => 'un',
                    'metro' => 'm',
                    'centimetro'   => 'cm',
                    'milimetro'   => 'mm',
                ])
                ->searchable()
                ->required(),
            TextInput::make('preco_custo')->label('Preço/Custo')->numeric()->prefix('R$')->required(),
            TextInput::make('estoque')->required(),
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
