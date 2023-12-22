<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PaymentAttemptHistory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{

    public $model = PaymentAttemptHistory::class;
    public $s = "pago";
    public $sp = "pagos";
    public $ss = "pago/s";
    public $v = "o"; 
    public $pr = "el"; 
    public $prp = "los";

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => ['required', Rule::exists('orders', 'id')],
            'data' => 'required',
        ]);

        try {
            $payment = new $this->model();
            $payment->order_id = $request->order_id;
            $payment->data = $request->data;
            $payment->save();

            $order = Order::find($request->order_id);
            $this->model::order_audit($order->order_number, [
                "info" => "Registro de pago exitoso",
                "data_sent" => $request->all()
            ]);
            
        } catch (Exception $error) {
            Log::debug("Error al registrar pago: " . $error->getMessage() . ' line: ' . $error->getLine());
            $this->model::order_audit($order->order_number, [
                "info" => "Error al registar pago",
                "data_sent" => $request->all(),
                "error_message" => $error->getMessage(), 
                "error_line" => $error->getLine(),
            ]);
            return response(["message" => "Error al registrar pago", "error" => $error->getMessage()], 500);
        }
       
        return response()->json(['message' => 'Informacion de pago guardada con exito.', 'payment' => $payment], 200);
    }
}
