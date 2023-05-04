<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Support\Collection;

class OrderRepository implements \App\Interfaces\OrderInterface
{


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

    function getAllOrders(int $user_id): Collection
    {
        return Order::query()->where('user_id','=',$user_id)->orderByDesc('created_at')->get();
    }
    function getAllAdmin(): Collection
    {
        return Order::all();
    }

    function getOrderById(int $id): Order|null
    {
        return Order::query()->where('id','=',$id)->get()->first();
    }
}
