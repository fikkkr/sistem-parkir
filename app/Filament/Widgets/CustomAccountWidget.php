<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Override;

class CustomAccountWidget extends Widget
{
    protected string $view = 'filament.widgets.custom-account-widget';

    protected int|string|array $columnSpan = 'full';
}
