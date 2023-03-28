<?php

namespace App\Interfaces;

use App\Models\Order;
use Dotenv\Repository\RepositoryInterface;
use Illuminate\Support\Collection;

interface OrderInterface extends RepositoryInterface
{
    function getAllOrders(int $user_id):Collection;
    function getOrderById(int $id):Order|null;
}
