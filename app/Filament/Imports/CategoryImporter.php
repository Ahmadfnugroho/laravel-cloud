<?php

namespace App\Filament\Imports;

use App\Models\Category;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Notifications\Notification;

class CategoryImporter extends Importer
{
    protected static ?string $model = Category::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('photo')
                ->rules(['max:255']),
        ];
    }


    public function resolveRecord(): ?Category
    {
        $category = new Category();
        $category->name = $this->data['name'];  // Mengisi field 'name' dengan data CSV
        $category->photo = $this->data['photo'] ?? null;  // Mengisi field 'photo' dengan data CSV
    
        // Simpan ke database
        $category->save();
    
        return $category;
    }
    

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your category import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
