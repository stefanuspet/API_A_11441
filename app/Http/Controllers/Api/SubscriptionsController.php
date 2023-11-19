<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Subscriptions;
use Illuminate\Validation\Rule;
use App\Models\User;

class SubscriptionsController extends Controller
{
    public function index()
    {
        $subscriptions = Subscriptions::with(['User'])->get();
        if (count($subscriptions) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $subscriptions
            ], 200);
        } else {
            return response([
                'message' => 'Empty',
                'data' => null
            ], 400);
        }
    }
    public function store(Request $request)
    {
        $storeData = $request->all();
        $user = User::find($storeData['id_user']);
        $validate = Validator::make($storeData, [
            'id_user' => 'required',
            'category' => ['required', Rule::in(['Basic', 'Standard', 'Premium'])],
        ]);

        $validate->after(function ($validator) use ($storeData) {
            $user = User::find($storeData['id_user']);
            if (!$user) {
                $validator->errors()->add('id_user', 'User not found.');
            } elseif ($user->status == 1) {
                $validator->errors()->add('id_user', 'User is already active.');
            }
        });

        if ($storeData['category'] == 'Basic') {
            $storeData['price'] = 50000;
        } elseif ($storeData['category'] == 'Standard') {
            $storeData['price'] = 100000;
        } elseif ($storeData['category'] == 'Premium') {
            $storeData['price'] = 150000;
        } else {
            return response(['message' => 'Invalid category. Please choose between basic, standard, or premium.'], 400);
        }

        $storeData['transaction_date'] = now();

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $subscriptions = Subscriptions::create($storeData);


        if ($user) {
            $user->status = 1;
            $user->save();
        }
        return response([
            'message' => 'Add Subscription Success',
            'data' => $subscriptions
        ], 200);
    }
    public function update(Request $request, $id)
    {
        $subscriptions = Subscriptions::find($id);
        if (is_null($subscriptions)) {
            return response([
                'message' => 'Subscription Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'id_user' => 'required',
            'category' => ['required', Rule::in(['Basic', 'Standard', 'Premium'])],
        ]);

        $validate->after(function ($validator) use ($updateData) {
            $user = User::find($updateData['id_user']);
            if (!$user) {
                $validator->errors()->add('id_user', 'User not found.');
            } elseif ($user->status == 0) {
                $validator->errors()->add('id_user', 'User is not active.');
            }
        });

        if ($updateData['category'] == 'Basic') {
            $updateData['price'] = 50000;
        } elseif ($updateData['category'] == 'Standard') {
            $updateData['price'] = 100000;
        } elseif ($updateData['category'] == 'Premium') {
            $updateData['price'] = 150000;
        } else {
            return response(['message' => 'Invalid category. Please choose between basic, standard, or premium.'], 400);
        }

        $updateData['transaction_date'] = now();

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $subscriptions->update($updateData);
        return response([
            'message' => 'Update Subscription Success',
            'data' => $subscriptions
        ], 200);
    }
    public function destroy($id)
    {
        $subscriptions = Subscriptions::find($id);
        if (is_null($subscriptions)) {
            return response([
                'message' => 'Subscription Not Found',
                'data' => null
            ], 404);
        }
        $user = User::find($subscriptions->id_user);
        if ($user) {
            $user->status = 0;
            $user->save();
        }
        if ($subscriptions->delete()) {
            return response([
                'message' => 'Delete Subscription Success',
                'data' => $subscriptions
            ], 200);
        }
        return response([
            'message' => 'Delete Subscription Failed',
            'data' => null
        ], 400);
    }
    public function show($id)
    {
        $subscriptions = Subscriptions::find($id);
        if (is_null($subscriptions)) {
            return response([
                'message' => 'Subscription Not Found',
                'data' => null
            ], 404);
        }
        return response([
            'message' => 'Retrieve Subscription Success',
            'data' => $subscriptions
        ], 200);
    }
}
