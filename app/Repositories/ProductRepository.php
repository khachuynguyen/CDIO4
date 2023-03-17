<?php

namespace App\Repositories;

use App\Interfaces\ProductInterface;
use App\Models\Product;
use Illuminate\Support\Collection;

class ProductRepository implements ProductInterface
{

    function getAllProduct(): Collection
    {
        return Product::all();
    }

    function findProductById(int $id): null|Product
    {
        return Product::find($id);
    }

    function deleteProduct(int $id): bool
    {
        return $this->findProductById($id)->delete();
    }
    public function updateProduct(array $arr)
    {
        $query = Product::query()->update($arr);
    }
    /**
     * @inheritDoc
     */
    public function has(string $name)
    {
        // TODO: Implement has() method.
    }

    /**
     * @inheritDoc
     */
    public function get(string $name)
    {
        // TODO: Implement get() method.
    }

    /**
     * @inheritDoc
     */
    public function set(string $name, string $value)
    {
        // TODO: Implement set() method.
    }

    /**
     * @inheritDoc
     */
    public function clear(string $name)
    {
        // TODO: Implement clear() method.
    }


}
