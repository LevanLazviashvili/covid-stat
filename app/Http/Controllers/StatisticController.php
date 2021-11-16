<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Statistic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticController extends Controller
{
    public function index(Request $request)
    {
        return response(Country::with('Statistics')->get());
    }

    public function summary(Request $request)
    {
        return response(Statistic::get(DB::raw('SUM(confirmed) AS confirmed, SUM(death) AS death, SUM(recovered) AS recover'))->first());
        // or less productive:
//        return response([
//           'confirmed'      => Statistic::sum('confirmed'),
//            'death'         => Statistic::sum('death'),
//            'recovered'     => Statistic::sum('recovered')
//        ]);
    }
}
