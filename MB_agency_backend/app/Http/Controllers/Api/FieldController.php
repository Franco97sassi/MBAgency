<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Field;
use App\Models\File;
use App\Models\Influencer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FieldController extends Controller
{
    public function index()
    {
        $items = Field::with(['influencer','group','file','story','statusVideo','category'])->get();
        return response()->json([
            'res' => true,
            'data' => $items
        ]);
    }


    function total()
    {
        $total = Field::select('total_coins')->sum('total_coins');
        return response()->json([
            'total_coins' => $total
        ]);
    }

    // 10 usuarios que más coins generaron la semana pasada
    function lastWeek()
    {
        $today = Carbon::parse('2023-07-17 00:00:00');
        // Retroceder una semana
        $lastWeek = $today->subWeek();
        // Obtener el día de la semana de la fecha actual (lunes = 1, domingo = 7)
        $dayWeek = $lastWeek->dayOfWeek;
        // Restar los días necesarios para llegar al lunes de la semana pasada
        $MondayLastWeek = $lastWeek->subDays($dayWeek - 1);
        // Sumar 6 días para obtener el domingo de la semana pasada
        $SundayLastWeek = $MondayLastWeek->copy()->addDays(6);

        $fields = Field::with(['influencer'])
            ->whereDate('created_at', '>=', $MondayLastWeek)
            ->whereDate('created_at', '<=', $SundayLastWeek)
            ->groupBy('influencer_id')
            ->select('influencer_id', DB::raw('SUM(total_coins) as suma_total_coins'))
            ->orderByDesc('suma_total_coins', 'desc')
            ->take(10)
            ->get();
        return $fields;
    }

    function today()
    {
        $lastFile = File::latest()->first();
        $fields = Field::with(['influencer'])->where('file_id', $lastFile->id)
            ->orderBy('total_coins', 'desc')
            ->take(10)
            ->get();
        return $fields;
    }

    function week()
    {
        $now = Carbon::now();
        // $now = Carbon::parse('2023-07-11');
        $Monday = $now->copy()->startOfWeek(Carbon::MONDAY);
        $fields = Field::with(['influencer'])
            ->whereDate('created_at', '>=', $Monday)
            ->groupBy('influencer_id')
            ->select('influencer_id', DB::raw('SUM(total_coins) as suma_total_coins'))
            ->orderByDesc('suma_total_coins', 'desc')
            ->take(10)
            ->get();
        return $fields;
    }

    function month()
    {
        $now = Carbon::now();
        // $now = Carbon::parse('2023-07-15');

        $firstMonth = $now->copy()->startOfMonth();

        $fields = Field::with(['influencer'])
            ->whereDate('created_at', '>=', $firstMonth)
            ->groupBy('influencer_id')
            ->select('influencer_id', DB::raw('SUM(total_coins) as suma_total_coins'))
            ->orderByDesc('suma_total_coins', 'desc')
            ->take(10)
            ->get();

        return $fields;
    }

    function chart()
    {
        $now = Carbon::now();
        // $now = Carbon::parse('2023-07-15');

        $firstMonth = $now->copy()->startOfMonth();

        /*$above = Field::whereDate('created_at', '>=', $firstMonth)
            ->groupBy('influencer_id', 'created_at')
            ->havingRaw('SUM(total_coins) > 20000')
            ->select('influencer_id', 'created_at', DB::raw('SUM(total_coins) as suma_total_coins'))
            ->get()->count();*/

        $above = DB::table('fields')
                ->select('fields.influencer_id', DB::raw('SUM(fields.total_coins) as total'), 'influencers.code', 'influencers.username')
                ->join('influencers', 'influencers.id', '=', 'fields.influencer_id', 'inner', true)
                ->where('fields.group_time', '>', $firstMonth)
                ->groupBy('fields.influencer_id', 'influencers.code', 'influencers.username')
                ->having('total', '>', 20000)
                ->get()->count();

        /*$between = Field::whereDate('created_at', '>=', $firstMonth)
            ->groupBy('influencer_id', 'created_at')
            ->havingRaw('10000 >= SUM(total_coins) <= 20000')
            ->select('influencer_id', 'created_at', DB::raw('SUM(total_coins) as suma_total_coins'))
            ->get()->count();*/

        $between = DB::table('fields')
                ->select('fields.influencer_id', DB::raw('SUM(fields.total_coins) as total'), 'influencers.code', 'influencers.username')
                ->join('influencers', 'influencers.id', '=', 'fields.influencer_id', 'inner', true)
                ->where('fields.group_time', '>', $firstMonth)
                ->groupBy('fields.influencer_id', 'influencers.code', 'influencers.username')
                ->having('total', '>', 10000)
                ->having('total', '<', 20000)
                ->get()->count();

        /*$below = Field::whereDate('created_at', '>=', $firstMonth)
            ->groupBy('influencer_id', 'created_at')
            ->havingRaw('SUM(total_coins) < 10000')
            ->select('influencer_id', 'created_at', DB::raw('SUM(total_coins) as suma_total_coins'))
            ->get()->count();*/

        $below = DB::table('fields')
                ->select('fields.influencer_id', DB::raw('SUM(fields.total_coins) as total'), 'influencers.code', 'influencers.username')
                ->join('influencers', 'influencers.id', '=', 'fields.influencer_id', 'inner', true)
                ->where('fields.group_time', '>', $firstMonth)
                ->groupBy('fields.influencer_id', 'influencers.code', 'influencers.username')
                ->having('total', '>', 0)
                ->having('total', '<', 10000)
                ->get()->count();

        /*$cero = Field::whereDate('created_at', '>=', $firstMonth)
            ->groupBy('influencer_id', 'created_at')
            ->havingRaw('SUM(total_coins) > 0')
            ->select('influencer_id', 'created_at', DB::raw('SUM(total_coins) as suma_total_coins'))
            ->get()->count();*/

        $cero = DB::table('fields')
                ->select('fields.influencer_id', DB::raw('SUM(fields.total_coins) as total'), 'influencers.code', 'influencers.username')
                ->join('influencers', 'influencers.id', '=', 'fields.influencer_id', 'inner', true)
                ->where('fields.group_time', '>', $firstMonth)
                ->groupBy('fields.influencer_id', 'influencers.code', 'influencers.username')
                ->having('total', '=', 0)
                ->get()->count();

        return response()->json([
            'above' => $above,
            'between' => $between,
            'below' => $below,
            'cero' => $cero
        ]);
    }

    function case0($case){
        $now = Carbon::now();
        // $now = Carbon::parse('2023-07-15');

        $firstMonth = $now->copy()->startOfMonth();
        $firstMonth = $firstMonth->format('yy-m-d 00:00:00');
        //return response()->json($firstMonth);
        switch ($case) {
            case '0':

                $above = DB::table('fields')
                        ->select('fields.influencer_id', DB::raw('SUM(fields.total_coins) as total'), 'influencers.code', 'influencers.username')
                        ->join('influencers', 'influencers.id', '=', 'fields.influencer_id', 'inner', true)
                        ->where('fields.group_time', '>', $firstMonth)
                        ->groupBy('fields.influencer_id', 'influencers.code', 'influencers.username')
                        ->having('total', '>', 20000)
                        ->get();
                /*$above = Field::with('influencer')->whereDate('group_time', '>=', '2022-12-00 00:00:00')
                        ->select('influencer_id', 'created_at', DB::raw('SUM(total_coins) as suma_total_coins'))
                        ->groupBy('influencer_id', 'created_at')
                        ->havingRaw('SUM(total_coins) > 20000')->get();*/

                        return response()->json($above);
                break;

            case '1':
                /*$above = Field::with('influencer')->whereDate('created_at', '>=', $firstMonth)
                        ->groupBy('influencer_id', 'created_at')
                        ->havingRaw('10000 >= SUM(total_coins) <= 20000')
                        ->select('influencer_id', 'created_at', DB::raw('SUM(total_coins) as suma_total_coins'))
                        ->get();*/
                $above = DB::table('fields')
                        ->select('fields.influencer_id', DB::raw('SUM(fields.total_coins) as total'), 'influencers.code', 'influencers.username')
                        ->join('influencers', 'influencers.id', '=', 'fields.influencer_id', 'inner', true)
                        ->where('fields.group_time', '>', $firstMonth)
                        ->groupBy('fields.influencer_id', 'influencers.code', 'influencers.username')
                        ->having('total', '>', 10000)
                        ->having('total', '<', 20000)
                        ->get();

                        return response()->json($above);
                break;

            case '2':
                /*$above = Field::with('influencer')->whereDate('created_at', '>=', $firstMonth)
                        ->groupBy('influencer_id', 'created_at')
                        ->havingRaw('SUM(total_coins) < 10000')
                        ->select('influencer_id', 'created_at', DB::raw('SUM(total_coins) as suma_total_coins'))
                        ->get();*/

                $above = DB::table('fields')
                        ->select('fields.influencer_id', DB::raw('SUM(fields.total_coins) as total'), 'influencers.code', 'influencers.username')
                        ->join('influencers', 'influencers.id', '=', 'fields.influencer_id', 'inner', true)
                        ->where('fields.group_time', '>', $firstMonth)
                        ->groupBy('fields.influencer_id', 'influencers.code', 'influencers.username')
                        ->having('total', '>', 0)
                        ->having('total', '<', 10000)
                        ->get();
                        return response()->json($above);
                break;

            case '3':
                /*$above = Field::with('influencer')->whereDate('created_at', '>=', $firstMonth)
                        ->groupBy('influencer_id', 'created_at')
                        ->havingRaw('SUM(total_coins) > 0')
                        ->select('influencer_id', 'created_at', DB::raw('SUM(total_coins) as suma_total_coins'))
                        ->get();*/
                $above = DB::table('fields')
                        ->select('fields.influencer_id', DB::raw('SUM(fields.total_coins) as total'), 'influencers.code', 'influencers.username')
                        ->join('influencers', 'influencers.id', '=', 'fields.influencer_id', 'inner', true)
                        ->where('fields.group_time', '>', $firstMonth)
                        ->groupBy('fields.influencer_id', 'influencers.code', 'influencers.username')
                        ->having('total', '=', 0)
                        ->get();

                        return response()->json($above);
                break;

            default:
                $above = Field::with('influencer')->whereDate('created_at', '>=', $firstMonth)
                        ->groupBy('influencer_id', 'created_at')
                        ->havingRaw('SUM(total_coins) > 20000')
                        ->select('influencer_id', 'created_at', DB::raw('SUM(total_coins) as suma_total_coins'))
                        ->get();

                        return response()->json($above);
                break;
        }




    }

    // function week()
    // {
    //     $today = Carbon::parse('2023-07-17 00:00:00');
    //     // $today = Carbon::now();
    //     // Retroceder una semana
    //     $lastWeek = $today->subWeek();
    //     // Obtener el día de la semana de la fecha actual (lunes = 1, domingo = 7)
    //     $dayWeek = $lastWeek->dayOfWeek;
    //     // Restar los días necesarios para llegar al lunes de la semana pasada
    //     $MondayLastWeek = $lastWeek->subDays($dayWeek - 1);
    //     // Sumar 6 días para obtener el domingo de la semana pasada
    //     $SundayLastWeek = $MondayLastWeek->copy()->addDays(6);


    //     $fields = Field::with(['influencer'])
    //         ->whereDate('created_at', '>=', $MondayLastWeek)
    //         ->whereDate('created_at', '<=', $SundayLastWeek)
    //         ->groupBy('influencer_id')
    //         ->select('influencer_id', DB::raw('SUM(total_coins) as suma_total_coins'))
    //         ->orderByDesc('suma_total_coins', 'desc')
    //         ->take(10)
    //         ->get();
    //     return $fields;
    // }

    // function month()
    // {
    //     $lastMonth = Carbon::parse('2023-08-17 00:00:00')->subMonth();
    //     // $lastMonth = Carbon::now()->subMonth();

    //     $first = $lastMonth->copy()->startOfMonth();
    //     $end = $lastMonth->copy()->endOfMonth();
    //     $fields = Field::with(['influencer'])
    //         ->whereDate('created_at', '>=', $first)
    //         ->whereDate('created_at', '<=', $end)
    //         ->groupBy('influencer_id')
    //         ->select('influencer_id', DB::raw('SUM(total_coins) as suma_total_coins'))
    //         ->orderByDesc('suma_total_coins', 'desc')
    //         ->take(10)
    //         ->get();

    //     return $fields;
    // }
}
