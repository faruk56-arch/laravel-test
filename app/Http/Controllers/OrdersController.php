<?php

namespace App\Http\Controllers;

use App\Models\AlertSubscription;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function index($id)
    {

        return response()->json(
            [
                'status' => 'success',
                'data' => Order::select(
                    'id',
                    'merchant_id',
                    'product_id',
                    'firstname',
                    'lastname',
                    'address',
                    'country_id',
                    'region',
                    'city',
                    'email',
                    'phone',
                    'zip'
                )
                    ->where('merchant_id', $id)
                    ->with('country', 'region', 'product')
                    ->latest()->get(),
            ],
            200
        );

    }

    public function getOrderById($id)
    {
        return response()->json(
            [
                'status' => 'success',
                'order' => Order::where('id', $id)->with('country', 'region', 'product')->first(),
            ],
            200
        );
    }

    public function allOrders(): JsonResponse
    {
        return response()->json(
            [
                'status' => 'success',
                'orders' => Order::select(
                    'id',
                    'firstname',
                    'lastname',
                    'address',
                    'platform_commission',
                    'your_earnings',
                    'platform_earnings',
                    'price',
                )->latest()->get()->toArray(),
            ],
            200
        );
    }


}
