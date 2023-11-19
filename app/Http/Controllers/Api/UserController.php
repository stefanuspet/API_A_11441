<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $user = User::all();
        if (count($user) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $user
            ], 200);
        } else {
            return response([
                'message' => 'Empty',
                'data' => null
            ], 400);
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 400);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:60',
            'email' => 'required',
            'password' => 'required|min:8',
            'no_telp' => 'required|regex:/^08[0-9]{9,11}$/',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            "image" => "tidak boleh kosong, file format: jpg,jpeg,png, ukuran file max 2 MB",
            "password.min" => "tidak boleh kosong, minimal 8 karakter",
            "no_telp.regex" => "tidak boleh kosong, hanya boleh berisi angka, diawali dengan 08, terdiri dari 11-13 digit"
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->no_telp = $request->no_telp;

        $image = $request->file('image');
        $imageName = $image->getClientOriginalName();
        $image->move(public_path('images'), $imageName);
        $user->image = $imageName;

        $user->save();

        return response([
            'message' => 'Update User Success',
            'data' => $user
        ], 200);
    }
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 400);
        }
        $user->delete();
        return response([
            'message' => 'Delete User Success',
            'data' => $user
        ], 200);
    }

    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 400);
        }
        return response([
            'message' => 'Retrieve User Success',
            'data' => $user
        ], 200);
    }
}
