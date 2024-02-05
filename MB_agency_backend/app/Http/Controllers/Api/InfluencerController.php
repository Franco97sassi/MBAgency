<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Influencer;
use Illuminate\Http\Request;

class InfluencerController extends Controller
{
    public function index()
    {
        $items = Influencer::all();
        return response()->json([
            'res' => true,
            'data' => $items
        ]);
    }


    public function store(Request $request)
    {
        $item = Influencer::create($request->all());

        return response()->json([
            'res' => true,
            'data' => $item,
            'message' => 'Información Registrada'
        ]);
    }

    public function show(Influencer $item)
    {
        return response()->json([
            'res' => true,
            'data' => $item,
        ]);
    }


    public function update(Request $request,  Influencer $item)
    {

        $item->update($request->all());

        return response()->json([
            'res' => true,
            'data' => $item,
            'message' => 'Información Actualizada'
        ]);
    }


    public function destroy(Influencer $item)
    {
        $item->delete();
        return response()->json([
            'res' => true,
            'data' => $item,
            'message' => 'Información Eliminada'
        ]);
    }

    public function restore($item)
    {
        $item = Influencer::onlyTrashed()->find($item);
        $item->restore();
        return response()->json([
            'res' => true,
            'data' => $item,
            'message' => 'Información Eliminada'
        ]);
    }

    public function forceDelete(Influencer $item)
    {
        $item = Influencer::onlyTrashed()->find($item);
        $item->forceDelete();
        return response()->json([
            'res' => true,
            'data' => $item,
            'message' => 'Información Eliminada'
        ]);
    }
}
