<?php

namespace App\Interfaces;

use App\Models\Cart;
use Dotenv\Repository\RepositoryInterface;
use Illuminate\Support\Collection;

interface CartsInterface extends RepositoryInterface
{
    function getAllCartsByUserId(int $user_id): Collection;

    function findCartsByUserIdAndProductId(int $user_id, int $product_id): Collection;

    function updateQuantity(int $user_id, int $product_id, int $count): bool;
    function sumTotalQuantityByProductId(int $product_id):int;
}
