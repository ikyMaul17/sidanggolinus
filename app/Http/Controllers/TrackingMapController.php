<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Transaksi;
use App\Models\HaltePulang;
use App\Models\HaltePergi;

class TrackingMapController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function tracking_map()
    {
      return view('tracking_map.index');
    }

    public function getBusPositions()
    {
        $buses = DB::table('tracking')
                ->select('tracking.id', 'bus.nama', 'latitude', 'longitude','users.nama as nama_supir')
                ->join('bus','bus.id','=','tracking.id_bus')
                ->join('users','users.id','=','tracking.id_supir')
                ->whereNotNull('latitude')
                ->get();

        return response()->json($buses);
    }

    public function getBusStops()
    {
        $haltePulang = HaltePulang::all(['id', 'nama as name', 'latitude', 'longitude']);
        $haltePergi = HaltePergi::all(['id', 'nama as name', 'latitude', 'longitude']);
        
        $stops = $haltePulang->merge($haltePergi);
        return response()->json($stops);
    }
}
