<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FileRequest;
use App\Models\File;
use App\Http\Requests\StoreFileRequest;
use App\Http\Requests\UpdateFileRequest;
use App\Http\Resources\FileResource;
use App\Imports\CategoryImport;
use App\Imports\DayImport;
use App\Imports\FieldImport;
use App\Imports\GroupImport;
use App\Imports\InfluencerImport;
use App\Imports\StatusVideoImport;
use App\Imports\StoryImport;
use App\Imports\WeekImport;
use App\Models\Field;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class FileController extends Controller
{
    public function index()
    {
        $items = File::all();
        return FileResource::collection($items)->additional([
            'res' => true
        ]);
    }


    public function store(FileRequest $request)
    {
        $file = $request->file('file');
        if ($file) {
            $uuid = Str::uuid();
            $url = $uuid . '.' . $file->getClientOriginalExtension(); // $file->getClientOriginalName();
            $nameFile = $file->getClientOriginalName();
            // Guardando Archivo
            $file->storeAs('File', $url); // Lugar donde se guardará

            $item = File::create([
                'name_file' =>  $nameFile,
                'name' => $url,
                'url' => 'storage/File/' . $url,
                'uuid' => $uuid,
                'user_id' => $request->user_id
            ]);


            Excel::import(new FieldImport($item->id), $file);


            return FileResource::make($item)->additional([
                'message' => 'Información Registrada'
            ]);
        } else {
            return response()->json([
                'res' => false,
                'message' => 'Error al cargar archivo'
            ]);
        }
    }

    public function show(File $item)
    {
        return FileResource::make($item);
    }


    public function update(FileRequest $request,  File $item)
    {
        $file = $request->file('file');
        $uuid = Str::uuid();
        $nameUrl = $uuid . '.' . $file->getClientOriginalExtension(); // $file->getClientOriginalName();
        $nameFile = $file->getClientOriginalName();
        $url = $uuid . '.' . $file->getClientOriginalExtension(); // $file->getClientOriginalName();

        //Eliminando el archivo
        Storage::delete(['File/' . $item->url]);

        // Guardando Archivo
        $file->storeAs('File', $nameUrl); // Lugar donde se guardará

        $item->update([
            'name' => $nameFile,
            'url' => $nameUrl,
            'uuid' => $uuid,
            'user_id' => $request->user_id
        ]);
        return FileResource::make($item)->additional([
            'message' => 'Información Actualizada'
        ]);
    }


    public function destroy(File $item)
    {
        Storage::delete(['File/' . $item->url]); //Eliminar el archivo

        $item->delete();
        return FileResource::make($item)->additional([
            'message' => 'Información Eliminada'
        ]);
    }

    function importExcel(Request $request)
    {
        $file = $request->file('file');
        // Grupal
        Excel::import(new InfluencerImport, $file);

        return response()->json([
            'res' => 'Importado Exitosamente'
        ]);
    }
    // Importar Influencers
    function importUserExcel(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            // Excel
            Excel::import(new InfluencerImport, $file);

            return response()->json([
                'res' => 'Importado Exitosamente'
            ]);
        }

        return response()->json([
            'res' => 'No se encontró ningún Archivo'
        ]);
    }
    // Importar Excel Diarios
    function importDayExcel(Request $request)
    {
        // Guardar Excel
        if ($request->hasFile('file')) {
            // Guardando Archivo
            $file = $request->file('file');
            $uuid = Str::uuid();
            $nameUrl = $uuid . '.' . $file->getClientOriginalExtension();
            $nameFile = $file->getClientOriginalName();
            $file->storeAs('File', $nameUrl);
            $path =
                $saved =   File::create([
                    'name_file' => $nameFile,
                    'name' => $nameUrl,
                    'uuid' => $uuid,
                    'user_id' => $request->user_id,
                    'url' => 'storage/File/' . $nameUrl,
                    'type' => 'day'
                ]);

            // Excel
            Excel::import(new DayImport($saved->id), $file);
            return response()->json([
                'res' => 'Excel Diario Importado Exitosamente'
            ]);
        }

        return response()->json([
            'res' => 'No se encontró ningún Archivo'
        ]);
    }


    // Importar Excel Semanal
    function importWeekExcel(Request $request)
    {
        // Guardar Excel
        if ($request->hasFile('file')) {
            // Guardando Archivo
            $file = $request->file('file');
            $uuid = Str::uuid();
            $nameUrl = $uuid . '.' . $file->getClientOriginalExtension();
            $nameFile = $file->getClientOriginalName();
            $file->storeAs('File', $nameUrl);
            $path =
                $saved =   File::create([
                    'name_file' => $nameFile,
                    'name' => $nameUrl,
                    'uuid' => $uuid,
                    'user_id' => $request->user_id,
                    'url' => 'storage/File/' . $nameUrl,
                    'type' => 'week'
                ]);

            // Excel
            Excel::import(new WeekImport($saved->id), $file);
            return response()->json([
                'res' => 'Excel Semanal Importado Exitosamente'
            ]);
        }

        return response()->json([
            'res' => 'No se encontró ningún Archivo'
        ]);
    }
}
