<?php

namespace App\Filament\Imports;

namespace App\Filament\Imports;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\SubCategory;
use App\Models\RentalInclude;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class ProductImporter extends Importer
{
    protected static ?string $model = Product::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('quantity')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('price')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('status')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('thumbnail')
                ->rules(['nullable', 'max:255']),

            ImportColumn::make('category')
                ->relationship('category', 'name', 'id')
                ->rules(['nullable']),
            ImportColumn::make('brand')
                ->relationship('brand', 'name', 'id')
                ->rules(['nullable']),
            ImportColumn::make('sub_category')
                ->relationship('subcategory', 'name', 'id')
                ->rules(['nullable']),
        ];
    }

    public function resolveRecord(): ?Product
    {
        $product = new Product();
        $product->name = $this->data['name'];
        $product->slug = Str::slug($this->data['name']);

        $category = $this->data['category'] ?? null;
        if ($category) {
            $category = Category::where('name', $category)->first();
            if ($category) {
                $product->category_id = $category->id;
            } else {
                $product->category_id = null;
            }
        }

        $brand = $this->data['brand'] ?? null;
        if ($brand) {
            $brand = Brand::where('name', $brand)->first();
            if ($brand) {
                $product->brand_id = $brand->id;
            } else {
                $product->brand_id = null;
            }
        }

        $SubCategory = $this->data['sub_category'] ?? null;
        if ($SubCategory) {
            $SubCategory = SubCategory::where('name', $SubCategory)->first();
            if ($SubCategory) {
                $product->sub_category_id = $SubCategory->id;
            } else {
                $product->sub_category_id = null;
            }
        } else {
            $product->sub_category_id = null;
        }

        $product->quantity = $this->data['quantity'] ?? 0;
        $product->price = $this->data['price'] ?? 0;
        $product->thumbnail = $this->data['thumbnail'] ?? '';
        $product->status = $this->data['status'] ?? 'available';

        try {
            // Simpan ke database
            $product->save();
        } catch (\Exception $e) {
            // Handle error
            throw new \Exception($e->getMessage());
        }

        return $product;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your product import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
