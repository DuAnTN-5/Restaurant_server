<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\ContactMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function sendContact(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $name = $request->input('name');
        $email = $request->input('email');
        $content = $request->input('content');

        Mail::to('hightfiverestaurant@gmail.com')->send(new ContactMail($name, $email, $content));

        return response()->json(['message' => 'Email đã được gửi thành công!'], 200);
    }
}
