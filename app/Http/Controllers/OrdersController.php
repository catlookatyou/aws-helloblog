<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Cart;
use App\Order;
use Auth;
use ECPay_PaymentMethod as ECPayMethod;
use ECPay_AllInOne as ECPay;

class OrdersController extends Controller
{
    public function index()
    {
        $orders = Auth::user()->orders;
        return view('store.orders', compact('orders'));
    }

    public function orders(){
        $orders = Order::all();
        return $orders;
    }

    public function new()
    {
        $oldCart = session()->has('cart') ? session()->get('cart') : null;
        $cart = new Cart($oldCart);
        return view('store.order',[
            'items'=> $cart->items,
            'totalPrice'=> $cart->totalPrice,
            'totalQty'=>$cart->totalQty]);
    }

    public function store()
    {
        request()->validate([
            'name' => 'required',
            'email' => 'required',
        ]);
        $cart = session()->get('cart');
	$user_id = Auth::user()->id;
        $uuid_temp = str_replace("-", "",substr(Str::uuid()->toString(), 0,18));
        $order = Order::create([
            'user_id' => $user_id,
	    'name' => request('name'),
            'email' => request('email'),
            'cart' => serialize($cart),
            'uuid' => $uuid_temp
            ]);
        // session()->flash('success', 'Order success!');
        //-----------------ecpay
        try {
            //$obj = new \ECPay_AllInOne();
            $obj=new ECPay();
            //服務參數
            $obj->ServiceURL  = "https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5";   //服務位置
            $obj->HashKey     = '5294y06JbISpM5x9' ;                                           //測試用Hashkey，請自行帶入ECPay提供的HashKey
            $obj->HashIV      = 'v77hoKGq4kWxNNIS' ;                                           //測試用HashIV，請自行帶入ECPay提供的HashIV
            $obj->MerchantID  = '2000132';                                                     //測試用MerchantID，請自行帶入ECPay提供的MerchantID
            $obj->EncryptType = '1';                                                           //CheckMacValue加密類型，請固定填入1，使用SHA256加密
            //基本參數(請依系統規劃自行調整)
            $MerchantTradeNo = $uuid_temp ;
            $obj->Send['ReturnURL']         = "http://ec2-3-19-65-228.us-east-2.compute.amazonaws.com/order/callback" ;    //付款完成通知回傳的網址
            $obj->Send['PeriodReturnURL']         = "http://ec2-3-19-65-228.us-east-2.compute.amazonaws.com/order/callback" ;    //付款完成通知回傳的網址
            $obj->Send['ClientBackURL'] = "http://ec2-3-19-65-228.us-east-2.compute.amazonaws.com/success" ;    //付款完成通知回傳的網址
            $obj->Send['MerchantTradeNo']   = $MerchantTradeNo;                          //訂單編號
            $obj->Send['MerchantTradeDate'] = date('Y/m/d H:i:s');                       //交易時間
            $obj->Send['TotalAmount']       = $cart->totalPrice;                        //交易金額
            $obj->Send['TradeDesc']         = "catlookatyou" ;                          //交易描述
            $obj->Send['ChoosePayment']     = ECPayMethod::Credit ;              //付款方式:Credit
            $obj->Send['IgnorePayment']     = ECPayMethod::GooglePay ;           //不使用付款方式:GooglePay
            //訂單的商品資料
            //$order_name = request('name')."的訂單";
            //$cart = session()->get('cart');
            $items = $cart->items; //constructor
            $arr = [];
            $order_name = '';
            foreach($items as $item){
                $info = $item['item']['name'].",".$item['price']."元"."×".$item['qty'];
                $arr[] = $info;
            }
            foreach($arr as $item){
                $order_name = $order_name.$item."#";
            }

            array_push($obj->Send['Items'], array('Name' => $order_name, 'Price' => $cart->totalPrice,
            'Currency' => "元", 'Quantity' => (int) "0", 'URL' => "dedwed"));
            //session()->forget('cart');
            $obj->CheckOut();
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        //session()->forget('cart');
        //return redirect('/orders');
    }

    public function callback()
    {
        // dd(request());
        $order = Order::where('uuid', '=', request('MerchantTradeNo'))->firstOrFail();
        $order->paid = 1;
        $order->save();
    }

    public function redirectFromECpay () {
        session()->forget('cart');
        session()->flash('success', 'Order success!');
        return redirect('/items');
    }

}
