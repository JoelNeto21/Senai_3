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

use UnitEnum;
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

    protected static string|UnitEnum|null $navigationGroup = 'Admin';
    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Cargos';
    protected static ?string $modelLabel = 'Cargo';
    protected static ?string $pluralModelLabel = 'Cargos';

    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole('Admin') ?? false;
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        RoleForm::configure($schema);
        return $schema 
        -> schema([
            TextInput::make('name')
            ->label('Nome do cargo')
            ->required()
            ->maxLength(50)
            ->unique(ignoreRecord: true)
            ->validationMessages([
                'required' => 'Informe o nome do cargo.',
                'unique' => 'Este cargo ja esta cadastrado.',
            ]),

            TextInput::make('guard_name')
            ->label('Guard')
            ->required()
            ->default('web')
            ->maxLength(50)
            ->validationMessages([
                'required' => 'Informe o guard.',
            ]),

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
            TextColumn::make('guard_name')->label('Guard')->searchable(),
            TextColumn::make('permissions.name')->label('Permissao')
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
