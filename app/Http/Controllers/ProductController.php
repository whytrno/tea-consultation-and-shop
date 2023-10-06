<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTrait;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    use ResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Product::with('category')->get();

        foreach ($data as $d) {
            $d->image = url('images/products') . '/' . $d->image;
        }

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
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,png,jpeg',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'qty' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->validationFailedResponse($validator->errors(), null, 422);
        }

        $image = $request->file('image');
        $image_name = time() . '.' . $image->extension();
        $image->move(public_path('images/products'), $image_name);

        $product = Product::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'image' => $image_name,
            'price' => $request->price,
            'description' => $request->description,
            'qty' => $request->qty,
        ]);

        return $this->successResponse($product, 'Data created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data = Product::with('category')->find($id);

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
        $data = Product::find($id);

        if (!$data) {
            return $this->failedResponse('Data not found', 404);
        }

        if ($request->hasFile('image')) {
            $image_path = public_path('images/products') . '/' . $data->image;
            if (file_exists($image_path)) {
                unlink($image_path);
            }

            $image = $request->file('image');
            $image_name = time() . '.' . $image->extension();
            $image->move(public_path('images/products'), $image_name);
        }

        $data->update([
            'category_id' => $request->category_id ?? $data->category_id,
            'name' => $request->name ?? $data->name,
            'image' => $image_name ?? $data->image,
            'price' => $request->price ?? $data->price,
            'description' => $request->description ?? $data->description,
            'qty' => $request->qty ?? $data->qty,
        ]);

        return $this->successResponse($data, 'Data updated successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = Product::find($id);

        if (!$data) {
            return $this->failedResponse('Data not found', 404);
        }

        $data->delete();

        return $this->successResponse(null, 'Data deleted successfully', 200);
    }
}