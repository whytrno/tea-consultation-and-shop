<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTrait;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Cart::with(['user', 'product'])
            ->where('user_id', Auth::user()->id)
            ->get();

        return $this->successResponse($data, null, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $existingProducts = Cart::where('user_id', Auth::user()->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($existingProducts) {
            $existingProducts->update([
                'qty' => $existingProducts->qty + $request->qty,
            ]);

            return $this->successResponse($existingProducts, 'Data updated successfully', 200);
        }

        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->validationFailedResponse($validator->errors(), null, 422);
        }

        $cart = Cart::create([
            'user_id' => Auth::user()->id,
            'product_id' => $request->product_id,
            'qty' => $request->qty,
        ]);

        return $this->successResponse($cart, 'Data created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = Cart::find($id);

        if (!$data) {
            return $this->failedResponse('Data not found', 404);
        }

        $validator = Validator::make($request->all(), [
            'qty' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->validationFailedResponse($validator->errors(), null, 422);
        }

        $data->update([
            'qty' => $request->qty,
        ]);

        return $this->successResponse($data, 'Data updated successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = Cart::find($id);

        if (!$data) {
            return $this->failedResponse('Data not found', 404);
        }

        $data->delete();

        return $this->successResponse(null, 'Data deleted successfully', 200);
    }
}