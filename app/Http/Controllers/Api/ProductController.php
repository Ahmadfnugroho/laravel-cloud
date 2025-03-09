<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Symfony\Component\CssSelector\Node\FunctionNode;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with([
            'brand',
            'subCategory',
            'rentalIncludes',
            'productSpecifications',
            'productPhotos',
        ])->get();

        return ProductResource::collection($products);
    }
    public function show(Product $product)
    {
        $product->load(
            'category', 
            'brand', 
            'subCategory', 
            'rentalIncludes.includedProduct', 
            'productSpecifications', 
            'productPhotos');
            return new ProductResource($product);
    }

    public function ProductsHome()
    {
        $products = Product::where('premiere', 1)
            ->with('category', 'brand')
            ->get();

        return ProductResource::collection($products);
    }

}