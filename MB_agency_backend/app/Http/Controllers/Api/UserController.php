<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $items = User::all();
        return UserResource::collection($items)->additional([
            'res' => true
        ]);
    }


    public function store(UserRequest $request)
    {
        $item = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id
        ]);

        return UserResource::make($item)->additional([
            'message' => 'Información Registrada'
        ]);
    }

    public function show(User $item)
    {
        return UserResource::make($item);
    }


    public function update(UserRequest $request,  User $item)
    {
        $item->update([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id
        ]);

        return  UserResource::make($item)->additional([
            'message' => 'Información Actualizada'
        ]);
    }


    public function destroy(User $item)
    {
        $item->delete();
        return UserResource::make($item)->additional([
            'message' => 'Información Eliminada'
        ]);
    }
}
