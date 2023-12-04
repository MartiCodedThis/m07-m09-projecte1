<?php

namespace App\Filament\Resources\VisibilityResource\Pages;

use App\Filament\Resources\VisibilityResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewVisibility extends ViewRecord
{
    protected static string $resource = VisibilityResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
