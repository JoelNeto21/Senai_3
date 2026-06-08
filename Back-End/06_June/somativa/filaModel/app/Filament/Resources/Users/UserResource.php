<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Pages\ViewUser;
use App\Filament\Resources\Users\Schemas\UserForm;
use App\Filament\Resources\Users\Schemas\UserInfolist;
use App\Filament\Resources\Users\Tables\UsersTable;
use App\Models\User;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components\Select; // <<<
use Filament\Forms\Components\TextInput; // <<<
use Filament\Tables\Columns\TextColumn; // <<<

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole('Admin') ?? false;
    }

    protected static string|UnitEnum|null $navigationGroup = 'Admin';
    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Usuários';
    protected static ?string $modelLabel = 'Usuário';
    protected static ?string $pluralModelLabel = 'Usuários';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        UserForm::configure($schema);
        return $schema 
        -> schema([
            TextInput::make('name')
            ->label('Nome de Usuário')
            ->required()
            ->maxLength(50),

            TextInput::make('email')
            ->label('E-mail')
            ->required()
            ->email()
            ->rules(['required', 'email:filter'])
            ->maxLength(255)
            ->unique(ignoreRecord: true)
            ->validationMessages([
                'required' => 'Informe o e-mail.',
                'email' => 'Informe um e-mail válido.',
                'unique' => 'Este e-mail já está cadastrado.',
            ]),
            
            TextInput::make('password')
            ->label('Senha')
            ->required(fn (string $operation): bool => $operation === 'create')
            ->password()
            ->minLength(6)
            ->maxLength(255)
            ->dehydrateStateUsing(fn ($state) => !empty($state) ? bcrypt($state) : null)
            ->dehydrated(fn ($state) => filled($state))
            ->validationMessages([
                'required' => 'Informe a senha.',
                'minLength' => 'A senha deve ter no mínimo 6 caracteres.',
            ]),

            Select::make('permissions') 
            -> label('Permissões de Acesso') 
            -> multiple() 
            -> relationship('permissions', 'name') 
            -> preload() 
            -> columnSpanFull(), 

            Select::make('roles') 
            -> label('Cargo')
            -> multiple() 
            -> relationship('roles', 'name') 
            -> preload() 
            -> columnSpanFull(), 
        ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return UserInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        UsersTable::configure($table);
        return $table
        -> columns([
            TextColumn::make('name')->label('Nome')->searchable(),
            TextColumn::make('email')->label('E-mail')->searchable(),
            TextColumn::make('roles.name')->label('Cargo')
            ->badge()
            ->sortable()
            ->searchable()
            ->color('info'),
            TextColumn::make('permissions.name')->label('Permissão')
            ->badge()
            ->sortable()
            ->searchable()
            ->color('success'),            
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
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'view' => ViewUser::route('/{record}'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
