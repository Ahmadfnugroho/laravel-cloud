<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->get();
        return CategoryResource::collection($categories);
    }
    public function show(Category $category)
    {
        $category->load([
            'products.category',
            'products.brand',
            'products.subCategory',
            'products.rentalIncludes',
            'products.productSpecifications',
            'products.productPhotos',
        ]);
        $category->loadCount('products');
        return new CategoryResource($category);
    }
}
