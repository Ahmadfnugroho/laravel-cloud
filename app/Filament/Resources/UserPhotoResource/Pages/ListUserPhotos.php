<?php

namespace App\Filament\Resources\UserPhotoResource\Pages;

use App\Filament\Resources\UserPhotoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserPhotos extends ListRecords
{
    protected static string $resource = UserPhotoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
