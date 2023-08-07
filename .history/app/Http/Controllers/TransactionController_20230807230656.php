<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTrait;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    use ResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Transaction::where('user_id', Auth::user()->id)->get();

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
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'payment_id' => 'required|exists:payments,id',
            'cart_id' => 'required|exists:carts,id',
            'total' => 'required|string',
            'approval_file' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'sent_to_address' => 'required|string',
            'message' => 'required|string',
            'sent_to_name' => 'nullable|string',
            'sent_to_phone' => 'nullable|string',

        ]);

        if ($validator->fails()) {
            return $this->validationFailedResponse($validator->errors(), null, 422);
        }

        $image = $request->file('image');
        $image_name = time() . '.' . $image->extension();
        $image->move(public_path('images/transactions'), $image_name);

        $product = Transaction::create([
            'user_id' => $request->user_id,
            'payment_id' => $request->payment_id,
            'cart_id' => $request->cart_id,
            'total' => $request->total,
            'approval_file' => $image_name,
            'sent_to_address' => $request->sent_to_address,
            'message' => $request->message,
            'sent_to_name' => $request->sent_to_name,
            'sent_to_phone' => $request->sent_to_phone,
        ]);

        return $this->successResponse($product, 'Data created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data = Transaction::find($id);

        return $this->successResponse($data, null, 200);
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
        $data = Transaction::find($id);

        if (!$data) {
            return $this->failedResponse('Data not found', 404);
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'nullable|exists:users,id',
            'payment_id' => 'nullable|exists:payments,id',
            'cart_id' => 'nullable|exists:carts,id',
            'total' => 'nullable|string',
            'approval_file' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'sent_to_address' => 'nullable|string',
            'message' => 'nullable|string',
            'sent_to_name' => 'nullable|string',
            'sent_to_phone' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->validationFailedResponse($validator->errors(), null, 422);
        }

        if ($request->hasFile('image')) {
            $image_path = public_path('images/transactions') . $data->image;
            if (file_exists($image_path)) {
                unlink($image_path);
            }

            $image = $request->file('image');
            $image_name = time() . '.' . $image->extension();
            $image->move(public_path('images/transactions'), $image_name);
        }

        $data->update([
            'user_id' => $request->user_id,
            'payment_id' => $request->payment_id,
            'cart_id' => $request->cart_id,
            'total' => $request->total,
            'approval_file' => $image_name,
            'sent_to_address' => $request->sent_to_address,
            'message' => $request->message,
            'sent_to_name' => $request->sent_to_name,
            'sent_to_phone' => $request->sent_to_phone,
        ]);

        return $this->successResponse($data, 'Data updated successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}