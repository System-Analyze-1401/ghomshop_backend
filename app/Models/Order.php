<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    public const STATUS_PENDING = '1';
    public const STATUS_PAID = '2';
    public const STATUS_DONE = '3';
    public const STATUS_CANCELED = '4';

    public static function addDraft($userId)
    {
        $order = new self();
        $order->user_id = $userId;
        $order->status = self::STATUS_PENDING;
        $order->save();

        return $order->id;
    }

    public static function verify($id)
    {
        $order = self::find($id);
        $order->status = self::STATUS_PAID;
        $order->update();
    }

    public static function cancel($id)
    {
        $order = self::find($id);
        $order->status = self::STATUS_CANCELED;
        $order->update();
    }

    public static function editOrder($request)
    {
        $order = self::find($request['id']);
        $order->status = $request['status'];
        $order->update();
    }

    public static function descriptionOrder($id)
    {
        return self::where('id', $id)->first();
    }

    public static function listOrder($request)
    {
        return self::select(
            'orders.*',
            'users.first_name',
            'users.last_name',
            DB::raw("CONCAT(first_name,' ',last_name) as user_full_name"),
        )
            ->when($request['search'], function ($query) use ($request) {
                return $query->where(
                    function ($query) use ($request) {
                            $query->where('user_full_name', 'like', '%' . $request['search'] . '%');
                        }
                );
            })
            ->join('users', 'users.id', 'orders.user_id')
            ->orderByDesc('users.id')
            ->get();
    }

    public static function listUserOrder($request, $userId)
    {
        return self::select(
            'orders.*',
            'users.first_name',
            'users.last_name',
            DB::raw("CONCAT(first_name,' ',last_name) as user_full_name"),
        )
            ->when($request['search'], function ($query) use ($request) {
                return $query->where(
                    function ($query) use ($request) {
                            $query->where('user_full_name', 'like', '%' . $request['search'] . '%');
                        }
                );
            })
            ->join('users', 'users.id', 'orders.user_id')
            ->where('user_id', $userId)
            ->orderByDesc('users.id')
            ->get();
    }
}