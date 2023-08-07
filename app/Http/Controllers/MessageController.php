<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTrait;
use App\Models\Chat;
use App\Models\Expert;
use App\Models\Message;
use App\Models\MessageMedia;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    use ResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
            'chat_id' => 'required|exists:chats,id',
            'sender_role' => 'required|in:customer,expert',
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->validationFailedResponse($validator->errors(), null, 422);
        }

        $data = Message::create([
            'chat_id' => $request->chat_id,
            'sender_role' => $request->sender_role,
            'message' => $request->message,
        ]);

        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $image) {
                $image_name = time() . '.' . $image->extension();
                $image->move(public_path('images/messages'), $image_name);

                MessageMedia::create([
                    'message_id' => $data->id,
                    'image' => $image_name,
                ]);
            }
        }

        return $this->successResponse($data, 'Data created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}