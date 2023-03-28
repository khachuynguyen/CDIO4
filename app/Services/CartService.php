<?php

namespace App\Services;

use App\Models\Cart;
use App\Repositories\CartRepository;
use App\Repositories\ProductRepository;
use Illuminate\Validation\ValidationException;
use function PHPUnit\Framework\isEmpty;

class CartService
{
    private CartRepository $cartRepository;
    private ProductRepository $productRepository;

    /**
     * @param CartRepository $cartRepository
     */
    public function __construct(CartRepository $cartRepository, ProductRepository $productRepository)
    {
        $this->cartRepository = $cartRepository;
        $this->productRepository = $productRepository;
    }

    public function getAllCartByUserId(int $user_id): \Illuminate\Support\Collection
    {
        return $this->cartRepository->getAllCartsByUserId($user_id);
    }

    public function addToCart(array $arr): bool
    {

        //get product  by id
        $product_found = $this->productRepository->findProductById($arr['product_id']);
        //check quantity in database and count total quantity in carts
        if ($product_found->quantity - $arr['count'] < 0)
            throw new \Exception("Check quantity", 422);
        if (count($this->cartRepository->findCartsByUserIdAndProductId($arr['user_id'], $arr['product_id']))) {
            $found = $this->cartRepository->findCartsByUserIdAndProductId($arr['user_id'], $arr['product_id'])->first();
//            $found->count = $found->count + $arr['count'];
            $found->count = $arr['count'];
            // check quantity of product and total quantity in carts
            if ($product_found->quantity - $found->count < 0)
                throw new \Exception("Check quantity", 422);
            $this->cartRepository->updateQuantity($arr['user_id'], $arr['product_id'], $found->count);
        } else {
            $cart = new Cart();
            foreach ($arr as $key => $value) {
                $cart->$key = $value;
            }
            $cart->avatar = $product_found->avatar;
            $cart->is_possible_to_order = 1;
            $cart->price = $product_found->price;
            $cart->product_total = $cart->price * $cart->count;
            $cart->save();
        }
        return true;
    }
    public function deleteCart(int $user_id, int $product_id): void
    {
        if (count($this->cartRepository->findCartsByUserIdAndProductId($user_id, $product_id))){
            $query = Cart::query();
            $query->where('user_id', '=', $user_id);
            $query->where('product_id', '=', $product_id);
            $query->delete();
            return;
        }
        throw new \Exception("not found cart", 404);
    }
}
