<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OrderDetail extends Model
{
    public $timestamps = false;

    public static function add($request, $orderId)
    {
        $orderDetail = new self();
        $orderDetail->order_id = $orderId;
        $orderDetail->product_id = $request['product_id'];
        $orderDetail->number = $request['number'];
        $orderDetail->save();
    }
    public static function listOrderDetailWithOrderId($orderId)
    {
        return self::select(
            'order_details.*',
            'products.*',
        )
            ->join('products', 'products.id', 'order_details.product_id')
            ->where('order_id', $orderId)
            ->orderByDesc('order_details.id')
            ->get();
    }
}