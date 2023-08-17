<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function tryPayments(Request $request)
    {
        $response = json_decode($request["kr-answer"]);
        return Payment::create([
            'code' => $response->orderDetails->orderId,
            'message' => 'log guardado',
            //'result' => $response->orderStatus,
            'result' => 1,
        ]);
    }
}
