<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    public function status(): HasOne
    {
        return $this->hasOne(OrderStatus::class, 'id', 'status_id');
    }

    public static function getAllOrder($id)
    {
        return Order::with('status')->find($id);
    }

    public static function newOrderAudit($order_id, $detail)
    {
        $order_audit = new OrderAudit();
        $order_audit->order_id = $order_id;
        $order_audit->detail = $detail;
        $order_audit->save();
    }

}