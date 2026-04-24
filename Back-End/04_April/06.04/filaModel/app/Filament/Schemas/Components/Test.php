<?php

namespace App\Filament\Schemas\Components;

use Filament\Schemas\Components\Component;

class Test extends Component
{
    protected string $view = 'filament.schemas.components.test';

    public static function make(): static
    {
        return app(static::class);
    }
}
