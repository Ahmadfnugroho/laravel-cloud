<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\BrandResource;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::withCount('products')->get();
        return BrandResource::collection($brands);
    }
    public function show(Brand $brand)
    {
        $brand->load([
            'products.category',
            'products.brand',
            'products.subCategory',
            'products.rentalIncludes',
            'products.productSpecifications',
            'products.productPhotos',
        ]);
        $brand->loadCount('products');
        return new BrandResource($brand);
    }


    public function getPremiereBrands()
{
    $brands = Brand::where('premiere', 1)->get();  
    return BrandResource::collection($brands);  // Menggunakan collection untuk mengembalikan banyak data
}

  
    
}
