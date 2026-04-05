<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Transaksi;
use PDF;

class HistoryBookingController extends Controller
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

    
    public function index(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
    
        $query = \DB::table('transaksi')
            ->join('halte_pergi', 'halte_pergi.id', '=', 'transaksi.id_penjemputan')
            ->join('halte_pulang', 'halte_pulang.id', '=', 'transaksi.id_tujuan')
            ->join('bus', 'bus.id', '=', 'transaksi.id_bus')
            ->join('users as p', 'p.id', '=', 'transaksi.id_penumpang')
            ->join('users as s', 's.id_bus', '=', 'bus.id')
            ->select(
                'transaksi.kode',
                'bus.nama as nama_bus',
                'halte_pergi.nama as halte_penjemputan',
                'halte_pulang.nama as halte_tujuan',
                'transaksi.status',
                'p.nama as nama_penumpang',
                's.nama as nama_supir',
                \DB::raw('DATE_FORMAT(transaksi.created_at, "%d-%m-%Y %H:%i:%s") as created_at')
            )
            ->orderBy('transaksi.id', 'desc');
    
        if ($startDate && $endDate) {
            $query->whereBetween('transaksi.created_at', [$startDate, $endDate]);
        }
    
        $data_booking = $query->get();
    
        return view('history_booking.index', compact('data_booking'));
    }


    public function exportPdf(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        $query = \DB::table('transaksi')
            ->join('halte_pergi', 'halte_pergi.id', '=', 'transaksi.id_penjemputan')
            ->join('halte_pulang', 'halte_pulang.id', '=', 'transaksi.id_tujuan')
            ->join('bus', 'bus.id', '=', 'transaksi.id_bus')
            ->join('users as p', 'p.id', '=', 'transaksi.id_penumpang')
            ->join('users as s', 's.id_bus', '=', 'bus.id')
            ->select(
                'transaksi.kode',
                'bus.nama as nama_bus',
                'halte_pergi.nama as halte_penjemputan',
                'halte_pulang.nama as halte_tujuan',
                'transaksi.status',
                'p.nama as nama_penumpang',
                's.nama as nama_supir',
                \DB::raw('DATE_FORMAT(transaksi.created_at, "%d-%m-%Y %H:%i:%s") as created_at')
            )
            ->orderBy('transaksi.id', 'desc');
    
        if ($startDate && $endDate) {
            $query->whereBetween('transaksi.created_at', [$startDate, $endDate]);
        }

        $data_booking = $query->get();

        $pdf = PDF::loadView('history_booking.pdf', compact('data_booking'))
              ->setPaper('a4', 'landscape'); // Set orientasi ke landscape

        return $pdf->stream('booking_report.pdf');
    }

}
