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
use Illuminate\Database\Eloquent\Builder;
use App\Rules\CpfValidation;
use App\Rules\PhoneValidation;

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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with([]);
    }

    protected static string|UnitEnum|null $navigationGroup = 'Cadastros Gerais';
    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nome';

    public static function form(Schema $schema): Schema
    {
        // Configure seu formulário base se necessário
        // ClienteForm::configure($schema); 

        return $schema->components([
            TextInput::make('nome')
                ->label('Nome Completo')
                ->required()
                ->validationMessages(['required' => 'Informe o nome completo.'])
                ->maxLength(255),

            TextInput::make('cpf')
                ->label('CPF')
                ->required()
                ->mask('999.999.999-99')
                ->stripCharacters(['.', '-', '/', ' '])
                ->dehydrateStateUsing(fn ($state) => preg_replace('/\D/', '', $state))
                ->maxLength(14)
                ->rules([new CpfValidation()])
                ->validationMessages([
                    'required' => 'Informe o CPF.',
                    'unique' => 'Este CPF já está cadastrado.',
                ])
                ->unique(ignoreRecord: true)
                ->helperText('Informe apenas os números do CPF.'),

            TextInput::make('email')
                ->label('E-mail')
                ->email()
                ->required()
                ->rules(['required', 'email:filter'])
                ->validationMessages([
                    'required' => 'Informe o e-mail.',
                    'email' => 'Informe um e-mail válido.',
                ])
                ->unique(ignoreRecord: true),

            TextInput::make('telefone')
                ->label('Telefone')
                ->tel()
                ->required()
                ->rules([new PhoneValidation()])
                ->validationMessages([
                    'required' => 'Informe o telefone.',
                ])
                ->stripCharacters(['(', ')', '-', ' '])
                ->dehydrateStateUsing(fn ($state) => preg_replace('/\D/', '', $state))
                ->mask(RawJs::make(<<<'JS'
                    $input.replace(/\D/g, '').length <= 10 
                        ? '(99) 9999-9999' 
                        : '(99) 99999-9999'
                JS))
                ->helperText('Ex: (11) 91234-5678 ou (11) 1234-5678'),
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
