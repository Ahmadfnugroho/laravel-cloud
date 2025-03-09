<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\SubCategoryResource;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    public function index()
    {
        $subCategory = SubCategory::withCount('products')->get();
        return SubCategoryResource::collection($subCategory);
    }
    
    public function show(SubCategory $subCategory)
    {
        $subCategory->load([
            'products.category',
            'products.brand',
            'products.subCategory',
            'products.rentalIncludes',
            'products.productSpecifications',
            'products.productPhotos',
        ]);
        $subCategory->loadCount('products');
        return new SubCategoryResource($subCategory);
    }
}
