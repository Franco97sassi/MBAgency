<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $items = Role::all();
        return RoleResource::collection($items)->additional([
            'res' => true
        ]);
    }


    public function store(RoleRequest $request)
    {
        $item = Role::create([
            'name' => $request->name

        ]);
        return RoleResource::make($item)->additional([
            'message' => 'Información Registrada'
        ]);
    }

    public function show(Role $item)
    {
        return RoleResource::make($item);
    }


    public function update(RoleRequest $request,  Role $item)
    {

        $item->update([
            "name" => $request->name
        ]);

        return  RoleResource::make($item)->additional([
            'message' => 'Información Actualizada'
        ]);;
    }


    public function destroy(Role $item)
    {
        $item->delete();
        return RoleResource::make($item)->additional([
            'message' => 'Información Eliminada'
        ]);
    }
}
