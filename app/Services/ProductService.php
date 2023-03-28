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
    public function getSuggestedProduct(Product $product): Collection
    {
        $found = $this->productRepository->getSuggestedProduct($product->manufacturer, $product->id);
        return $found;
    }

    public function getAllManufacturers(): array
    {
        $data = $this->productRepository->getAllManufacturers();
        $array_manufacturers = [];
        foreach ($data as  $item){
            array_push($array_manufacturers,$item->manufacturer);
        }
        return $array_manufacturers;
    }

    public function getAllProducts(): Collection
    {
        return $this->productRepository->getAllProduct();
    }
    public function createProduct(array $arr): Product|null
    {
        $product = new Product();
        foreach ($arr as $key => $value) {
            $product->$key = $value;
        }
        $product->price =  $product->cost - (int) ($product->cost *($product->percent)/100);
        if($product->save())
            return $product;
        return null;
    }

    public function updateProduct(int $id,array $arr): bool
    {
        $product = $this->getProductById($id);
        foreach ($arr as $key => $value) {
            $product->$key = $value;
        }
        $product->price =  $product->cost - (int)($product->cost *($product->percent)/100);
        return $product->save();
    }
    public function deleteProduct(int $id): bool
    {
        $product = $this->getProductById($id);
        return $this->productRepository->deleteProduct($id);
    }

    public function searchProducts(array $array_keys):Collection
    {
        return $this->productRepository->searchProduct($array_keys);
    }

}
