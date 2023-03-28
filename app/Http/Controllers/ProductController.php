<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
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
    public function getAllManufacturers(): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->productService->getAllManufacturers();
            return response()->json($data, 200);
        } catch (\Exception $exception) {
            return response()->json(null, 500);
        }
    }
    public function searchProduct(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $array_keys = $request->all();
            $data = $this->productService->searchProducts($array_keys);
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
            $suggest = $this->productService->getSuggestedProduct($found);
            $data = [
                'product' => $found,
                'suggest' => $suggest
            ];
            return response()->json($data, 200);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 404);
        }
    }
    public function updateProduct(int $id, UpdateProductRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $product_data  = $request->all();
            $is_success = $this->productService->updateProduct($id,$product_data);
            return response()->json($is_success, 200);
        }catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 404);
        }
    }
    public function deleteProduct(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $is_success = $this->productService->deleteProduct($id);
            return response()->json($is_success, 200);
        }catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 404);
        }
    }

}
