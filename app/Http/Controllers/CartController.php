<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddToCartRequest;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    private CartService $cartService;

    /**
     * @param CartService $cartService
     */
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function getCarts()
    {
        $user_id = Auth::id();
        if (!$user_id)
            return redirect('/login');
        return view('carts', ['list' => $this->cartService->getAllCartByUserId($user_id)]);
    }

    public function getAllCarts(): \Illuminate\Http\JsonResponse
    {
        try {
            $user_id = Auth::id();
            $carts = $this->cartService->getAllCartByUserId($user_id);
            for ($i = 0; $i < count($carts); $i++) {
                $product_name = Product::query()->where('id','=',$carts[$i]->product_id)->get('product_name')->first()['product_name'];
                $carts[$i]['product_name'] = $product_name;
            }
            return response()->json($carts, 200);
        } catch (\Exception $exception) {
            return response()->json(null, 500);
        }
    }

    public function addToCarts(AddToCartRequest $request): \Illuminate\Http\JsonResponse
    {
        DB::beginTransaction();
        try {
            $user_id = Auth::id();
            $data = [
                'product_id' => $request->get('product_id'),
                'count' => $request->get('count'),
                'user_id' => $user_id
            ];
            $is_success = $this->cartService->addToCart($data);
            DB::commit();
            return response()->json($is_success, 200);
        } catch (\Exception $exception) {
            DB::rollBack();
            if ($exception->getCode() == 422)
                return response()->json($exception->getMessage(), $exception->getCode());
            return response()->json(null, 500);
        }
    }

    public function deleteCart(int $product_id): \Illuminate\Http\JsonResponse
    {
        try {
            $user_id = Auth::id();
            $this->cartService->deleteCart($user_id, $product_id);
            return response()->json("delete cart successfully", 200);
        } catch (\Exception $exception) {
            if ($exception->getCode() == 404)
                return response()->json($exception->getMessage(), $exception->getCode());
            return response()->json(null, 500);
        }
    }

    public function updateQuantity(int $product_id): \Illuminate\Http\JsonResponse
    {
        try {
            $user_id = Auth::id();
            $this->cartService->deleteCart($user_id, $product_id);
            return response()->json("delete cart successfully", 200);
        } catch (\Exception $exception) {
            if ($exception->getCode() == 404)
                return response()->json($exception->getMessage(), $exception->getCode());
            return response()->json(null, 500);
        }
    }

}
