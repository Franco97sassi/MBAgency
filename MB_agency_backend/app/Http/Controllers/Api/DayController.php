<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Day;
use App\Models\File;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

class DayController extends Controller
{
    public function index()
    {
        $items = Day::with([])->get();
        return response()->json([
            'res' => true,
            'data' => $items
        ]);
    }


    function total()
    {
        $total = Day::select('total_coins')->sum('total_coins');
        return response()->json([
            'total_coins' => $total
        ]);
    }

    // 10 usuarios que más coins generaron la semana pasada
    function lastWeek()
    {
        $today = Carbon::now();
        // Retroceder una semana
        $lastWeek = $today->subWeek();
        // Obtener el día de la semana de la fecha actual (lunes = 1, domingo = 7)
        $dayWeek = $lastWeek->dayOfWeek;
        // Restar los días necesarios para llegar al lunes de la semana pasada
        $MondayLastWeek = $lastWeek->subDays($dayWeek - 1);
        // Sumar 6 días para obtener el domingo de la semana pasada
        $SundayLastWeek = $MondayLastWeek->copy()->addDays(6);

        $fields = Day::with(['influencer'])
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
        $lastFile = File::where('type', 'day')->latest()->first();
        $fields = Day::with(['influencer'])->where('file_id', $lastFile->id)
            ->orderBy('total_coins', 'desc')
            ->take(10)
            ->get();
        return $fields;
    }

    function week()
    {
        $now = Carbon::now();
        $Monday = $now->copy()->startOfWeek(Carbon::MONDAY);
        $fields = Day::with(['influencer'])
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

        $firstMonth = $now->copy()->startOfMonth();

        $fields = Day::with(['influencer'])
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
        $firstMonth = $now->copy()->startOfMonth();

        $above = DB::table('days')
            ->select('days.influencer_id', DB::raw('SUM(days.total_coins) as total'), 'influencers.code', 'influencers.username')
            ->join('influencers', 'influencers.id', '=', 'days.influencer_id', 'inner', true)
            ->where('days.group_time', '>', $firstMonth)
            ->groupBy('days.influencer_id', 'influencers.code', 'influencers.username')
            ->having('total', '>', 20000)
            ->get()->count();

        $between = DB::table('days')
            ->select('days.influencer_id', DB::raw('SUM(days.total_coins) as total'), 'influencers.code', 'influencers.username')
            ->join('influencers', 'influencers.id', '=', 'days.influencer_id', 'inner', true)
            ->where('days.group_time', '>', $firstMonth)
            ->groupBy('days.influencer_id', 'influencers.code', 'influencers.username')
            ->having('total', '>', 10000)
            ->having('total', '<', 20000)
            ->get()->count();

        $below = DB::table('days')
            ->select('days.influencer_id', DB::raw('SUM(days.total_coins) as total'), 'influencers.code', 'influencers.username')
            ->join('influencers', 'influencers.id', '=', 'days.influencer_id', 'inner', true)
            ->where('days.group_time', '>', $firstMonth)
            ->groupBy('days.influencer_id', 'influencers.code', 'influencers.username')
            ->having('total', '>', 0)
            ->having('total', '<', 10000)
            ->get()->count();

        $cero = DB::table('days')
            ->select('days.influencer_id', DB::raw('SUM(days.total_coins) as total'), 'influencers.code', 'influencers.username')
            ->join('influencers', 'influencers.id', '=', 'days.influencer_id', 'inner', true)
            ->where('days.group_time', '>', $firstMonth)
            ->groupBy('days.influencer_id', 'influencers.code', 'influencers.username')
            ->having('total', '=', 0)
            ->get()->count();

        return response()->json([
            'above' => $above,
            'between' => $between,
            'below' => $below,
            'cero' => $cero
        ]);
    }

    function case($case)
    {
        $now = Carbon::now();

        $firstMonth = $now->copy()->startOfMonth();
        $firstMonth = $firstMonth->format('yy-m-d 00:00:00');

        switch ($case) {
            case '0':

                $above = DB::table('days')
                    ->select('days.influencer_id', DB::raw('SUM(days.total_coins) as total'), 'influencers.code', 'influencers.username')
                    ->join('influencers', 'influencers.id', '=', 'days.influencer_id', 'inner', true)
                    ->where('days.group_time', '>', $firstMonth)
                    ->groupBy('days.influencer_id', 'influencers.code', 'influencers.username')
                    ->having('total', '>', 20000)
                    ->get();

                return response()->json($above);
                break;

            case '1':
                $above = DB::table('days')
                    ->select('days.influencer_id', DB::raw('SUM(days.total_coins) as total'), 'influencers.code', 'influencers.username')
                    ->join('influencers', 'influencers.id', '=', 'days.influencer_id', 'inner', true)
                    ->where('days.group_time', '>', $firstMonth)
                    ->groupBy('days.influencer_id', 'influencers.code', 'influencers.username')
                    ->having('total', '>', 10000)
                    ->having('total', '<', 20000)
                    ->get();

                return response()->json($above);
                break;

            case '2':
                $above = DB::table('days')
                    ->select('days.influencer_id', DB::raw('SUM(days.total_coins) as total'), 'influencers.code', 'influencers.username')
                    ->join('influencers', 'influencers.id', '=', 'days.influencer_id', 'inner', true)
                    ->where('days.group_time', '>', $firstMonth)
                    ->groupBy('days.influencer_id', 'influencers.code', 'influencers.username')
                    ->having('total', '>', 0)
                    ->having('total', '<', 10000)
                    ->get();
                return response()->json($above);
                break;

            case '3':
                $above = DB::table('days')
                    ->select('days.influencer_id', DB::raw('SUM(days.total_coins) as total'), 'influencers.code', 'influencers.username')
                    ->join('influencers', 'influencers.id', '=', 'days.influencer_id', 'inner', true)
                    ->where('days.group_time', '>', $firstMonth)
                    ->groupBy('days.influencer_id', 'influencers.code', 'influencers.username')
                    ->having('total', '=', 0)
                    ->get();

                return response()->json($above);
                break;

            default:
                $above = Day::with('influencer')->whereDate('created_at', '>=', $firstMonth)
                    ->groupBy('influencer_id', 'created_at')
                    ->havingRaw('SUM(total_coins) > 20000')
                    ->select('influencer_id', 'created_at', DB::raw('SUM(total_coins) as suma_total_coins'))
                    ->get();

                return response()->json($above);
                break;
        }
    }


    function dateBetween(Request $request){
        $request->validate([
            'start' => 'required|date',
            'end' => 'required|date'
        ]);

        $start = Carbon::parse($request->start);
        $end = Carbon::parse($request->end);

        $items = Day::where('group_time', '>=', $start)->where('group_time', '<=', $end)->get();

        return response()->json($items);
    }

    function listAll(){
        $items = Day::orderBy('id','desc')->paginate(10);
        return response()->json($items);
    }
}
