<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Support\Collection;

class ProductService
{
    private ProductRepository $productRepository;

    /**
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @return ProductRepository
     */
    public function getProductById(int $id): Product
    {
        $found = $this->productRepository->findProductById($id);
        if (!$found)
            throw new \Exception("Not found product", 404);
        return $found;
    }

    public function getAllProducts(): Collection
    {
        return $this->productRepository->getAllProduct();
    }

    public function createProduct(array $arr): bool
    {
        $product = new Product();
        foreach ($arr as $key => $value) {
            $product->$key = $value;
        }
        $savedProduct = $product->save();
        return $savedProduct;
    }

    public function updateProduct(int $id,array $arr): bool
    {
        $product = $this->getProductById($id);
        foreach ($arr as $key => $value) {
            $product->$key = $value;
        }
        return $product->save();
    }
    public function deleteProduct(int $id): bool
    {
        $product = $this->getProductById($id);
        return $this->productRepository->deleteProduct($id);
    }

}
