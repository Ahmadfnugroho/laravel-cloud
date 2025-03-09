<?php

namespace App\Filament\Resources\BundlingResource\Pages;

use App\Filament\Resources\BundlingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBundling extends EditRecord
{
    protected static string $resource = BundlingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
