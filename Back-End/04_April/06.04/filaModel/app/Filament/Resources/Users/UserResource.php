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

    protected static string|UnitEnum|null $navigationGroup = 'Admin';
    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Usuários';
    protected static ?string $modelLabel = 'Usuário';
    protected static ?string $pluralModelLabel = 'Usuários';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'User';

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
            ->label('Email')
            ->required()
            ->maxLength(50),
            
            TextInput::make('password')
            ->label('Senha')
            ->required()
            ->maxLength(20),

            Select::make('permissions') 
            -> label('Permissões de Acesso') 
            -> multiple() 
            -> relationship('permissions', 'name') 
            -> preload() 
            -> columnSpanFull(), 

            Select::make('roles') 
            -> label('Role/Cargo') 
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
            TextColumn::make('email')->label('Email')->searchable(),
            TextColumn::make('password')->label('Senha')->searchable(),
            TextColumn::make('roles.name')->label('Role')
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
