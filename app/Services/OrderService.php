<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Repositories\CartRepository;
use App\Repositories\OrderDetailRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use Illuminate\Support\Collection;

class OrderService
{
    private OrderRepository $orderRepository;
    private CartRepository $cartRepository;
    private OrderDetailRepository $orderDetailRepository;
    private ProductRepository $productRepository;

    /**
     * @param OrderRepository $orderRepository
     * @param CartRepository $cartRepository
     * @param OrderDetailRepository $orderDetailRepository
     * @param ProductRepository $productRepository
     */
    public function __construct(OrderRepository $orderRepository, CartRepository $cartRepository, OrderDetailRepository $orderDetailRepository, ProductRepository $productRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->cartRepository = $cartRepository;
        $this->orderDetailRepository = $orderDetailRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * @param OrderRepository $orderRepository
     * @param CartRepository $cartRepository
     * @param OrderDetailRepository $orderDetailRepository
     */
    private function checkValidateQuantity(int $product_id, int $quantity): bool
    {
        $found = $this->productRepository->findProductById($product_id);
        if (!$found || $found->quantity - $quantity < 0)
            throw new \Exception("Quantity is not enough to order", 422);
        return true;
    }

    public function createOrder(array $data): Order|null
    {
        $list_product = $data['list_product'];
        $order = new Order();
        $order->user_id = $data['user_id'];
        $order->paymentMethods = 'offline';
        $order->is_transported = false;
        $order->is_success = false;
        $order->is_payment = false;
        $order->total = 0;
        $order->save();
        $listCarts = $this->cartRepository->getAllCartsByUserId( $data['user_id']);
        if(count($listCarts) == 0)
            throw new \Exception("",404);
//        foreach ($listCarts as $item) {
//            $product_found = $this->productRepository->findProductById($item['product_id']);
//            $order_detail = new OrderDetail();
//            if ($this->checkValidateQuantity($product_found->id, $item->count)) {
//                $order_detail->product_id = $product_found->id;
//                $order_detail->order_id = $order->id;
//                $order_detail->quantity = $item->count;
//                $order_detail->price = $item->price;
//                $order_detail->save();
//                $query = Cart::query();
//                $query->where('user_id', '=', $order->user_id);
//                $query->where('product_id', '=', $product_found->id);
//                $query->delete();
//                $product_found->quantity -= $order_detail->quantity;
//                $product_found->save();
//                $order->total += $order_detail->quantity * $order_detail->price;
//            }
//        }
        foreach ($data['list_product'] as $product_id) {
            $product_found = $this->productRepository->findProductById($product_id);
            $cart_found = $this->cartRepository->findCartsByUserIdAndProductId($data['user_id'], $product_id)->first();
            if (!$cart_found)
                throw new \Exception("cart does not exist", 422);
            $order_detail = new OrderDetail();
            if ($this->checkValidateQuantity($product_id, $cart_found->count)) {
                $order_detail->product_id = $product_id;
                $order_detail->order_id = $order->id;
                $order_detail->quantity = $cart_found->count;
                $order_detail->price = $cart_found->price;
                $order_detail->save();
                $query = Cart::query();
                $query->where('user_id', '=', $order->user_id);
                $query->where('product_id', '=', $product_id);
                $query->delete();
                $product_found->quantity -= $order_detail->quantity;
                $product_found->save();
                $order->total += $order_detail->quantity * $order_detail->price;
            }
        }
        $order->save();
        return $order;
    }

    public function getAllOrder(int $id):Collection
    {
        return $this->orderRepository->getAllOrders($id);
    }
    public function getAllOrderAdmin():Collection
    {
        return $this->orderRepository->getAllAdmin();
    }
    public function getOrderById(int $id):Order|null
    {
        $order = $this->orderRepository->getOrderById($id);
        return $order;
    }
    public function getOrderDetailById(int $id):Collection
    {
        $list_order_detail = $this->orderDetailRepository->getAllOrderDetailByOrderId($id);
        return $list_order_detail;
    }

}
