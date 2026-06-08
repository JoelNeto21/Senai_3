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
use Illuminate\Database\Eloquent\Builder;
use App\Rules\CpfOrCnpjValidation;
use App\Rules\PhoneValidation;
use App\Support\BrazilianFormat;

use UnitEnum;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Support\RawJs;

class FornecedorResource extends Resource
{
    protected static ?string $model = Fornecedor::class;

    public static function canAccess(): bool {
        return auth()->user()?->hasRole('Admin') ?? false;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }

    protected static string|UnitEnum|null $navigationGroup = 'Cadastros Gerais';
    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Fornecedores';
    protected static ?string $modelLabel = 'Fornecedor';
    protected static ?string $pluralModelLabel = 'Fornecedores';


    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nome';

    public static function form(Schema $schema): Schema
    {
        FornecedorForm::configure($schema);

        return $schema->components([
            TextInput::make('nome')
                ->required()
                ->label('Nome/Fantasia')
                ->validationMessages(['required' => 'Informe o nome ou fantasia.'])
                ->maxLength(255),

            TextInput::make('cnpj')
                ->required()
                ->label('CNPJ/CPF')
                ->mask(RawJs::make(<<<'JS'
                    $input.replace(/\D/g, '').length > 11 
                        ? '99.999.999/9999-99' 
                        : '999.999.999-99'
                JS))
                ->stripCharacters(['.', '-', '/', ' '])
                ->dehydrateStateUsing(fn ($state) => preg_replace('/\D/', '', $state))
                ->maxLength(18)
                ->rules([new CpfOrCnpjValidation()])
                ->validationMessages([
                    'required' => 'Informe o CNPJ ou CPF.',
                    'unique' => 'Este CNPJ/CPF já está cadastrado.',
                ])
                ->unique(ignoreRecord: true)
                ->helperText('Informe CPF (11 dígitos) ou CNPJ (14 dígitos).'),

            TextInput::make('email')
                ->required()
                ->email()
                ->label('E-mail')
                ->maxLength(255)
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
        return FornecedorInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        FornecedorsTable::configure($table);
        return $table ->
        columns([
            TextColumn::make('nome')->label('Nome/Fantasia')->searchable(),
            TextColumn::make('cnpj')->label('CNPJ/CPF')->searchable()
                ->formatStateUsing(fn ($state) => BrazilianFormat::cpfCnpj($state)),
            TextColumn::make('email')->label('E-mail'),
            TextColumn::make('telefone')
                ->formatStateUsing(fn ($state) => BrazilianFormat::phone($state)),
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
