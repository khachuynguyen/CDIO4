<?php

namespace App\Repositories;

use App\Models\OrderDetail;

class OrderDetailRepository implements \App\Interfaces\OrderDetailInterface
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
    function getAllOrderDetailByOrderId(int $id):\Illuminate\Support\Collection
    {
        $query = OrderDetail::query()->where('order_id','=',$id);
        $data = $query->get();
        return $data;
    }
}
