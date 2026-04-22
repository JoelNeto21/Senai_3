<?php

namespace App\Filament\Resources\Clientes;

use App\Filament\Resources\Clientes\Pages\CreateCliente;
use App\Filament\Resources\Clientes\Pages\EditCliente;
use App\Filament\Resources\Clientes\Pages\ListClientes;
use App\Filament\Resources\Clientes\Pages\ViewCliente;
use App\Filament\Resources\Clientes\Schemas\ClienteForm;
use App\Filament\Resources\Clientes\Schemas\ClienteInfolist;
use App\Filament\Resources\Clientes\Tables\ClientesTable;
use App\Models\Cliente;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

use UnitEnum;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Support\RawJs;

class ClienteResource extends Resource
{
    protected static ?string $model = Cliente::class;

    public static function canAccess(): bool {
        return auth()->user()?->hasRole('Admin') ?? false;
    }

    protected static string|UnitEnum|null $navigationGroup = 'Cadastros Gerais';
    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Cliente';

    public static function form(Schema $schema): Schema
    {
        // Configure seu formulário base se necessário
        // ClienteForm::configure($schema); 

        return $schema->components([
            TextInput::make('nome')
                ->label('Nome Completo')
                ->required()
                ->maxLength(255),

            TextInput::make('cpf')
                ->label('CPF')
                ->required()
                ->mask('999.999.999-99')
                // ->dehydrateStateUsing(fn ($state) => preg_replace('/\D/', '', $state))
                ->maxLength(18),

            TextInput::make('email')
                ->label('E-mail')
                ->email()
                ->required(),

            TextInput::make('telefone')
                ->label('Telefone')
                ->tel()
                ->mask(RawJs::make(<<<'JS'
                    $input.replace(/\D/g, '').length <= 10 
                        ? '(99) 9999-9999' 
                        : '(99) 99999-9999'
                JS)),
        ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ClienteInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        ClientesTable::configure($table);
        return $table ->
        columns([
            TextColumn::make('nome')->searchable(),
            TextColumn::make('cpf')->label('CPF')->searchable(),
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
            'index' => ListClientes::route('/'),
            'create' => CreateCliente::route('/create'),
            'view' => ViewCliente::route('/{record}'),
            'edit' => EditCliente::route('/{record}/edit'),
        ];
    }
}
