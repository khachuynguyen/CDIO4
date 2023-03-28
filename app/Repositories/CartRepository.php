<?php

namespace App\Repositories;

use App\Models\Cart;
use Illuminate\Support\Collection;

class CartRepository implements \App\Interfaces\CartsInterface
{

    function getAllCartsByUserId(int $user_id): Collection
    {
        $query = Cart::query();
        $query->where('user_id', '=', $user_id);
        return $query->get();
    }

    function findCartsByUserIdAndProductId(int $user_id, int $product_id): Collection
    {
        $query = Cart::query();
        $query->where('user_id', '=', $user_id);
        $query->where('product_id', '=', $product_id);
        return $query->get();
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


    function updateQuantity(int $user_id, int $product_id, int $count): bool
    {
        $query = Cart::query();
        $query->where('user_id', '=', $user_id);
        $query->where('product_id', '=', $product_id);
        $query->update(['count'=>$count]);
        return true;
    }

    function sumTotalQuantityByProductId(int $product_id): int
    {
        $query = Cart::query();
        $query->where('product_id','=',$product_id);
        return $query->sum('count');
    }
}
