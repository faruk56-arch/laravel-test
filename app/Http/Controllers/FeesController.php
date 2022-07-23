<?php

namespace App\Http\Controllers;

use App\Models\Fee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\RequiredIf;

class FeesController extends Controller
{
    public function getAllProducts(): JsonResponse
    {
        return response()->json(
            [
                'products' => DB::table('products')->select('id', 'name')->get()
            ]
        );
    }

    public function getAllMerchants(): JsonResponse
    {
        return response()->json(
            [
                'merchants' => DB::table('merchants')->select('id', 'merchant_name')->get()
            ]
        );
    }

    public function getAllPiecesStatus(): JsonResponse
    {
        return response()->json(
            DB::table('status')->select('id', 'label')->get()
        );
    }

    public function getAllFees(): JsonResponse
    {
        return response()->json(
            [
                'fees' => DB::table('fees')
                    ->select('id', 'fee_type', 'ids', 'percent', 'user_type', 'piece_status', 'min_price', 'max_price')
                    ->get()
            ]
        );
    }

    public function AddFee(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'fee_type' => 'required',
            'ids' => new RequiredIf($request['fee_type'] != "all"),
            'percent' => 'required',
            'min_price' => 'nullable',
            'max_price' => 'nullable',
            'piece_status' => 'nullable',
            'user_type' => 'required',
            'filter_type' => 'required',
        ]);

        if (in_array("errors", $validated)) {
            return response()->json($validated, 422);
        } else {
            Fee::query()->create([
                'fee_type' => $request['fee_type'],
                'ids' => $request['ids'],
                'percent' => $request['percent'],
                'min_price' => $request['min_price'],
                'max_price' => $request['max_price'],
                'piece_status' => $request['piece_status'],
                'user_type' => $request['user_type'],
                'filter_type' => $request['filter_type'],
            ]);
            return response()->json(
                [
                    'data' => $request->all()
                ]
                , 200
            );
        }
    }

    public function getFee($id): JsonResponse
    {
        return response()->json(Fee::query()->find($id), 200);
    }

    public function updateFee(Request $request, $id): JsonResponse
    {
        $validated = $request->validate([
            'fee_type' => 'required',
            'ids' => new RequiredIf($request['fee_type'] != "all"),
            'percent' => 'required',
            'min_price' => 'nullable',
            'max_price' => 'nullable',
            'piece_status' => 'nullable',
            'user_type' => 'required',
            'filter_type' => 'required',

        ]);

        if (in_array("errors", $validated))
            return response()->json($validated, 422);
        else {
            Fee::query()->find($id)->update([
                'fee_type' => $request['fee_type'],
                'ids' => $request['ids'],
                'percent' => $request['percent'],
                'min_price' => $request['min_price'],
                'max_price' => $request['max_price'],
                'piece_status' => $request['piece_status'],
                'user_type' => $request['user_type'],
                'filter_type' => $request['filter_type'],
            ]);
            return response()->json(['data' => $request->all()]
                , 200
            );
        }
    }

    public function deleteFee($id)
    {
        $fee = Fee::find($id);
        if ($fee)
            $fee->delete();
        else
            return response()->json("this fee not exists", 404);


    }
}
