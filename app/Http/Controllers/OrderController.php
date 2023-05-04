<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\OnlinePaymentRequest;
use App\Models\Order;
use App\Repositories\CartRepository;
use App\Repositories\ProductRepository;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    private OrderService $orderService;
    private CartRepository $cartRepository;
    private ProductRepository $productRepository;

    /**
     * @param OrderService $orderService
     */
    public function __construct(OrderService $orderService, CartRepository $cartRepository, ProductRepository $productRepository)
    {
        $this->orderService = $orderService;
        $this->cartRepository = $cartRepository;
        $this->productRepository = $productRepository;
    }
    public function getAllOrders(Request $request): \Illuminate\Http\JsonResponse
    {
        $orders = [];
        if(Auth::user()->role == "ADMIN")
            $orders = $this->orderService->getAllOrderAdmin();
        else
            $orders = $this->orderService->getAllOrder(Auth::id());
        return response()->json($orders,200);
    }
    public function createOrder(Request $request):Order|null{
        DB::beginTransaction();
        try {
            $data = [];
            $data['user_id']= Auth::id();
            $data['list_product']= $request->get('listProduct');
            $order = $this->orderService->createOrder($data);
            DB::commit();
            return $order;
        }catch (\Exception $exception){
            DB::rollBack();
            return null;
        }
    }
    public function offlinePayment(Request $request): \Illuminate\Http\JsonResponse
    {
        $order = $this->createOrder($request);
        if($order)
            return response()->json($order,200);
        return response()->json("Failed",500);
    }
    public function getOrderByOderId(Request $request, int $orderId): \Illuminate\Http\JsonResponse
    {
        $order = $this->orderService->getOrderById($orderId);
        $list_order_detail = $this->orderService->getOrderDetailById($orderId);
        foreach ($list_order_detail as $item) {
            $item['avatar'] = $this->productRepository->findProductById($item->product_id)->avatar;
            $item['product_name'] = $this->productRepository->findProductById($item->product_id)->product_name;
        }
        if($order)
            return response()->json(['order'=>$order,'list_order_detail'=>$list_order_detail],200);
        return response()->json("Failed",500);
    }
    public function adminUpdateStatusOrder(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $user= Auth::user();
            if(strtoupper($user->role) != "ADMIN")
                throw new \Exception("Unauth",422);
            $order = Order::query()->where('id','=', $id)->get()->first();
            $order->is_success = $request->get('is_success');
            $bool = $order->save();
            return response()->json($bool,200);
        }catch (\Exception $exception){
            if($exception->getCode() == 422 )
                return response()->json($exception->getMessage(),$exception->getCode());
            return response()->json(null,500);
        }

    }
    public function updateInfoOrder(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $order = Order::query()->where('id','=',(int) $request->get('orderId'))->get()->first();
            if(Auth::id() != $order->user_id)
                throw new \Exception("Unauth",422);
            $order->is_payment =true;
            if($request->get('vnp_BankTranNo') == null)
                $order->is_payment =false;
            $order->paymentMethods = $request->get('vnp_CardType');
            $order->trading_code = $request->get('vnp_BankTranNo');

            $bool = $order->save();
            return response()->json($bool,200);
        }catch (\Exception $exception){
            if($exception->getCode() == 422 )
                return response()->json($exception->getMessage(),$exception->getCode());
            return response()->json(null,500);
        }


    }
    public function onlinePayment(Request $request, int $id){
        $order = Order::query()->where('id','=',$id)->get()->first();
        if(!$order)
            return response()->json("failed",500);
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = $request->get('return_url');
        $vnp_TmnCode = "KWPCU4B6";//Mã website tại VNPAY
        $vnp_HashSecret = "SWXZDCSBGTFJSQPMCXEQGIUSTDJITQFL"; //Chuỗi bí mật
        $vnp_TxnRef = "DONHANG".$order->id; //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
        $vnp_OrderInfo = "THANH TOAN";
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $order->total * 100;
        $vnp_Locale = 'vn';
        $vnp_IpAddr = request()->ip();
        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
//            "vnp_ExpireDate"=>$vnp_ExpireDate,
//            "vnp_Bill_Mobile"=>$vnp_Bill_Mobile,
//            "vnp_Bill_Email"=>$vnp_Bill_Email,
//            "vnp_Bill_FirstName"=>$vnp_Bill_FirstName,
//            "vnp_Bill_LastName"=>$vnp_Bill_LastName,
//            "vnp_Bill_Address"=>$vnp_Bill_Address,
//            "vnp_Bill_City"=>$vnp_Bill_City,
//            "vnp_Bill_Country"=>$vnp_Bill_Country,
//            "vnp_Inv_Phone"=>$vnp_Inv_Phone,
//            "vnp_Inv_Email"=>$vnp_Inv_Email,
//            "vnp_Inv_Customer"=>$vnp_Inv_Customer,
//            "vnp_Inv_Address"=>$vnp_Inv_Address,
//            "vnp_Inv_Company"=>$vnp_Inv_Company,
//            "vnp_Inv_Taxcode"=>$vnp_Inv_Taxcode,
//            "vnp_Inv_Type"=>$vnp_Inv_Type
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
            $inputData['vnp_Bill_State'] = $vnp_Bill_State;
        }

//var_dump($inputData);
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret);//
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        $returnData = array('code' => '00'
        , 'message' => 'success'
        , 'data' => $vnp_Url);
        if (isset($_POST['redirect'])) {
            header('Location: ' . $vnp_Url);
            die();
        } else {
            return response($vnp_Url,200);
        }
    }
}
