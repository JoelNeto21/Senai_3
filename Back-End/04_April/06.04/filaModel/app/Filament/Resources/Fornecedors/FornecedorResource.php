<?php

namespace App\Filament\Resources\Fornecedors;

use App\Filament\Resources\Fornecedors\Pages\CreateFornecedor;
use App\Filament\Resources\Fornecedors\Pages\EditFornecedor;
use App\Filament\Resources\Fornecedors\Pages\ListFornecedors;
use App\Filament\Resources\Fornecedors\Pages\ViewFornecedor;
use App\Filament\Resources\Fornecedors\Schemas\FornecedorForm;
use App\Filament\Resources\Fornecedors\Schemas\FornecedorInfolist;
use App\Filament\Resources\Fornecedors\Tables\FornecedorsTable;
use App\Models\Fornecedor;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

use UnitEnum;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Support\RawJs;

use App\Filament\Schemas\Components\Test;

class FornecedorResource extends Resource
{
    protected static ?string $model = Fornecedor::class;

    public static function canAccess(): bool {
        return auth()->user()?->hasRole('Admin') ?? false;
    }

    protected static string|UnitEnum|null $navigationGroup = 'Cadastros Gerais';
    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Fornecedores';
    protected static ?string $modelLabel = 'Fornecedor';
    protected static ?string $pluralModelLabel = 'Fornecedores';


    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Fornecedor';

    public static function form(Schema $schema): Schema
    {
        FornecedorForm::configure($schema);

        return $schema->components([
            Test::make(),

            TextInput::make('nome')
                ->required()
                ->label('Nome/Fantasia')
                ->maxLength(255),

            TextInput::make('cnpj')
                ->required()
                ->label('CNPJ/CPF')
                ->mask(RawJs::make(<<<'JS'
                    $input.replace(/\D/g, '').length > 11 
                        ? '99.999.999/9999-99' 
                        : '999.999.999-99'
                JS)),
                // ->dehydrateStateUsing(fn ($state) => preg_replace('/\D/', '', $state)),

            TextInput::make('email')
                ->required()
                ->email()
                ->label('E-mail'),

            TextInput::make('telefone')
                ->label('Telefone')
                ->tel()
                ->mask(RawJs::make(<<<'JS'
                    $input.replace(/\D/g, '').length <= 10 
                        ? '(99) 9999-9999' 
                        : '(99) 99999-9999'
                JS)),
                // ->dehydrateStateUsing(fn ($state) => preg_replace('/\D/', '', $state)),
        ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return FornecedorInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        FornecedorsTable::configure($table);
        return $table ->
        columns([
            TextColumn::make('nome')->label('Nome/Fantasia')->searchable(),
            TextColumn::make('cnpj')->label('CNPJ')->searchable(),
            TextColumn::make('email')->label('E-mail'),
            TextColumn::make('telefone'),
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
            'index' => ListFornecedors::route('/'),
            'create' => CreateFornecedor::route('/create'),
            'view' => ViewFornecedor::route('/{record}'),
            'edit' => EditFornecedor::route('/{record}/edit'),
        ];
    }
}
