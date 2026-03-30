<?php

namespace App\Filament\Resources\Permissions;

use App\Filament\Resources\Permissions\Pages\CreatePermission;
use App\Filament\Resources\Permissions\Pages\EditPermission;
use App\Filament\Resources\Permissions\Pages\ListPermissions;
use App\Filament\Resources\Permissions\Pages\ViewPermission;
use App\Filament\Resources\Permissions\Schemas\PermissionForm;
use App\Filament\Resources\Permissions\Schemas\PermissionInfolist;
use App\Filament\Resources\Permissions\Tables\PermissionsTable;
// use App\Models\Permission;
use Spatie\Permission\Models\Permission; // <<<
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput; // <<<
use Filament\Tables\Columns\TextColumn; // <<<

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    // public static function canAccess(): bool {
    //     return auth()->user()?->hasRole('Admin') ?? false;
    //     // return auth()->user()?->can('Free') ?? false;
    // }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Permissões';

    public static function form(Schema $schema): Schema
    {
        PermissionForm::configure($schema);
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
        ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PermissionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        PermissionsTable::configure($table);
        return $table
        -> columns([
            TextColumn::make('name')->label('Nome')->searchable(),
            TextColumn::make('guard_name')->label('Sigla')->searchable(),
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
            'index' => ListPermissions::route('/'),
            'create' => CreatePermission::route('/create'),
            'view' => ViewPermission::route('/{record}'),
            'edit' => EditPermission::route('/{record}/edit'),
        ];
    }
}
