<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderAudit;
use App\Models\OrderStatus;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

    public $model = Order::class;
    public $s = "orden";
    public $sp = "ordenes";
    public $ss = "orden/s";
    public $v = "n"; 
    public $pr = "la"; 
    public $prp = "las";

    public function store(Request $request)
    {
        $request->validate([
            'order_number' => 'required',
        ]);

        $order = $this->model::where('order_number', $request->order_number)->first();
        if($order)
            return response()->json(['message' => 'Orden ya existente.'], 400);
        
        try {
            $order = new $this->model();
            $order->order_number = $request->order_number;
            $order->save();

            $this->model::newOrderAudit($request->order_number, [
                "info" => "Creación de orden exitosa",
                "data_sent" => $request->all(),
                "error_message" => null, 
                "error_line" => null,
            ]);

            $this->model::newOrderStatusHistory(OrderStatus::PENDIENTE, $order->id);
            
        } catch (Exception $error) {
            Log::debug("Error al guardar orden: " . $error->getMessage() . ' line: ' . $error->getLine());
            $this->model::newOrderAudit($request->order_number, [
                "info" => "Error al guardar orden",
                "data_sent" => $request->all(),
                "error_message" => $error->getMessage(), 
                "error_line" => $error->getLine(),
            ]);
            return response(["message" => "Error al guardar orden", "error" => $error->getMessage()], 500);
        }

        $order = $this->model::getAllOrder($order->id);

        return response()->json(['message' => 'Orden guardada con exito.', 'order' => $order], 200);
    }
}
