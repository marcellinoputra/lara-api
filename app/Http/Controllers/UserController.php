<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function signUp(UserRegisterRequest $request): JsonResponse
    {
        $data = $request->validated();

        if (User::all()->where('username', $data['username'])->count() == 1) {
            throw new HttpResponseException(response([
                'errors' => [
                    'username' => [
                        'Username Already Exist'
                    ]
                ]
            ], 400));
        }

        $user = new User($data);
        $user->password = Hash::make($data['password']);
        $user->save();

        return (new UserResource($user))->response()->setStatusCode(201);
    }

    public function signIn(UserLoginRequest $req): UserResource
    {
        $data = $req->validated();

        $user = User::all()->where('username', $data['username'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw new HttpResponseException(response([
                'errors' => [
                    'message' => [
                        'Wrong Username or Password'
                    ]
                ]
            ], 401));
        }

        $user->token = Str::uuid()->toString();
        $user->save();

        return new UserResource($user);
    }

    public function getUser(Request $req): UserResource
    {
        $user = Auth::user();
        return new UserResource($user);
    }

    public function updateUser(UserUpdateRequest $req, $id): UserResource
    {
        $data = $req->validated();

        $user = User::all()->find($id);

        $user->update($data);

        // if ($data['username']) {
        //     $user->username = $data->username;
        // }
        // if ($data['password']) {
        //     $user->password = Hash::make($data['password']);
        // }
        // if ($data['name']) {
        //     $user->name = $data->name;
        // }


        $user->save();

        return new UserResource($user);
    }

    public function deleteUser($id)
    {
        // $user = User::all()->find($id);
        // $user->delete();

        // dd($id);

        $status = User::destroy($id);

        // return (new UserResource($user))->response()->setStatusCode(200);
        return response()->json([
            'status' => $status,
            'message' => $status ? 'User Deleted' : 'Error Deleting User'
        ]);
    }
}
