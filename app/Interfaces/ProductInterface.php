<?php

namespace App\Interfaces;

use App\Models\Product;
use Illuminate\Support\Collection;

interface ProductInterface extends \Dotenv\Repository\RepositoryInterface
{
    function getAllProduct():Collection;
    function findProductById(int $id):null|Product;
}
