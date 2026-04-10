<?php

namespace App\Filament\Resources\Roles;

use App\Filament\Resources\Roles\Pages\CreateRole;
use App\Filament\Resources\Roles\Pages\EditRole;
use App\Filament\Resources\Roles\Pages\ListRoles;
use App\Filament\Resources\Roles\Pages\ViewRole;
use App\Filament\Resources\Roles\Schemas\RoleForm;
use App\Filament\Resources\Roles\Schemas\RoleInfolist;
use App\Filament\Resources\Roles\Tables\RolesTable;
// use App\Models\Role;
use Spatie\Permission\Models\Role; // <<<
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components\Select; // <<<
use Filament\Forms\Components\TextInput; // <<<
use Filament\Tables\Columns\TextColumn; // <<<

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    // public static function canAccess(): bool {
    //     return auth()->user()?->hasRole('Admin') ?? false;
    //     // return auth()->user()?->can('Free') ?? false;
    // }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Cargos e Funções';

    public static function form(Schema $schema): Schema
    {
        RoleForm::configure($schema);
        return $schema 
        -> schema([
            TextInput::make('name')
            ->label('Nome da Regra')
            ->required()
            ->maxLength(50),

            TextInput::make('guard_name')
            ->label('Sigla')
            ->required()
            ->maxLength(50),

            Select::make('permissions') 
            -> label('Permissões de Acesso') 
            -> multiple() 
            -> relationship('permissions', 'name') 
            -> preload() 
            -> columnSpanFull(), 
        ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return RoleInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        RolesTable::configure($table);
        return $table
        -> columns([
            TextColumn::make('name')->label('Nome')->searchable(),
            TextColumn::make('guard_name')->label('Sigla')->searchable(),
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
            'index' => ListRoles::route('/'),
            'create' => CreateRole::route('/create'),
            'view' => ViewRole::route('/{record}'),
            'edit' => EditRole::route('/{record}/edit'),
        ];
    }
}
