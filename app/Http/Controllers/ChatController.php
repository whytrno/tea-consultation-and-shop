<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTrait;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    use ResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Chat::with(['user', 'expert', 'messages.medias'])
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
        $validator = Validator::make($request->all(), [
            'expert_id' => 'required|exists:experts,id',
        ]);

        if ($validator->fails()) {
            return $this->validationFailedResponse($validator->errors(), null, 422);
        }

        $user_id = Auth::user()->id;

        $data = Chat::create([
            'user_id' => $user_id,
            'expert_id' => $request->expert_id,
        ]);

        return $this->successResponse($data, 'Data created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data = Chat::with(['user', 'expert', 'messages'])
            ->where('user_id', Auth::user()->id)
            ->find($id);

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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = Chat::find($id);

        if (!$data) {
            return $this->failedResponse('Data not found', 404);
        }

        $data->delete();

        return $this->successResponse(null, 'Data deleted successfully', 200);
    }
}