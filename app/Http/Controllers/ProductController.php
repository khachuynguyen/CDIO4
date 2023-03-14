<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    private ProductService $productService;

    /**
     * @param ProductService $productService
     */
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function getAllProducts()
    {
        try {
            $data = $this->productService->getAllProducts();
            return response()->json($data, 200);
        } catch (\Exception $exception) {
            return response()->json(null, 500);
        }
    }

    public function createProduct(CreateProductRequest $request): \Illuminate\Http\JsonResponse
    {
        $product_data = $request->all();
        $savedProduct = $this->productService->createProduct($product_data);
        return response()->json($savedProduct, 201);
    }

    public function getProductById(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $found = $this->productService->getProductById($id);
            return response()->json($found, 200);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 404);
        }
    }

}
