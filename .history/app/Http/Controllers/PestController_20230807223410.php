<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTrait;
use App\Models\Pest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PestController extends Controller
{
    use ResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Pest::get();

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
            'name' => 'required|max:255|unique:pests,name',
            'image' => 'required|image|mimes:jpg,png,jpeg',
            'detail' => 'required|string',
            'description' => 'required|string',
            'how_to_controll' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->validationFailedResponse($validator->errors(), null, 422);
        }

        $image = $request->file('image');
        $image_name = time() . '.' . $image->extension();
        $image->move(public_path('images'), $image_name);

        $product = Pest::create([
            'name' => $request->name,
            'image' => $image_name,
            'detail' => $request->detail,
            'description' => $request->description,
            'how_to_controll' => $request->how_to_controll,
            'qty' => $request->qty,
        ]);

        return $this->successResponse($product, 'Data created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data = Pest::find($id);

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
        $data = Pest::find($id);

        if (!$data) {
            return $this->failedResponse('Data not found', 404);
        }

        if ($request->hasFile('image')) {
            $image_path = public_path('images') . '/' . $data->image;
            if (file_exists($image_path)) {
                unlink($image_path);
            }

            $image = $request->file('image');
            $image_name = time() . '.' . $image->extension();
            $image->move(public_path('images'), $image_name);
        }

        $data->update([
            'name' => $request->name ?? $data->name,
            'image' => $image_name ?? $data->image,
            'detail' => $request->detail ?? $data->detail,
            'description' => $request->description ?? $data->description,
            'how_to_controll' => $request->how_to_controll ?? $data->how_to_controll,
        ]);

        return $this->successResponse($data, 'Data updated successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = Pest::find($id);

        if (!$data) {
            return $this->failedResponse('Data not found', 404);
        }

        $data->delete();

        return $this->successResponse(null, 'Data deleted successfully', 200);
    }
}