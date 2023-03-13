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
        return $this->productRepository->findProductById($id);
    }
    public function getAllProducts():Collection{
        return $this->productRepository->getAllProduct();
    }
    public function createProduct(array $arr):bool{
        $product = new Product();
        foreach ($arr as $key=>$value){
            $product->$key = $value;
        }
        $savedProduct = $product->save();
        return $savedProduct;
    }
}
