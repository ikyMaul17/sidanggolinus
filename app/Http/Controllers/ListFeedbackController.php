<?php

namespace App\Http\Controllers;

use DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use App\Models\FeedbackSupir;
use App\Models\FeedbackPenumpang;

class ListFeedbackController extends Controller
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
    public function list_feedback_penumpang(Request $request)
    {
       $startDate = $request->input('startDate');
       $endDate = $request->input('endDate');
       $tipe = $request->input('tipe');

       $query = FeedbackPenumpang::orderBy('id', 'desc');

       if ($startDate && $endDate) {
            $startDate = Carbon::parse($startDate)->startOfDay(); // 2024-02-27 00:00:00
            $endDate = Carbon::parse($endDate)->endOfDay(); // 2024-02-27 23:59:59

            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

       if ($tipe) {
            $query->where('tipe', $tipe);
        }

       $data_feedback = $query->get();

      return view('feedback_penumpang.index', compact('data_feedback'));
    }

    public function exportPdfPenumpang(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $tipe = $request->input('tipe');
        
        $query = FeedbackPenumpang::orderBy('id', 'desc');

        if ($startDate && $endDate) {
            $startDate = Carbon::parse($startDate)->startOfDay(); // 2024-02-27 00:00:00
            $endDate = Carbon::parse($endDate)->endOfDay(); // 2024-02-27 23:59:59

            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        if ($tipe) {
            $query->where('tipe', $tipe);
        }

        $data_feedback = $query->get();

        $pdf = PDF::loadView('feedback_penumpang.pdf', compact('data_feedback'));
        return $pdf->stream('feedback_penumpang_report.pdf');
    }

    public function list_feedback_supir(Request $request)
    {
       $startDate = $request->input('startDate');
       $endDate = $request->input('endDate');
       $tipe = $request->input('tipe');

       $query = FeedbackSupir::orderBy('id', 'desc');

       if ($startDate && $endDate) {
            $startDate = Carbon::parse($startDate)->startOfDay(); // 2024-02-27 00:00:00
            $endDate = Carbon::parse($endDate)->endOfDay(); // 2024-02-27 23:59:59

            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

       if ($tipe) {
            $query->where('tipe', $tipe);
        }

       $data_feedback = $query->get();

      return view('feedback_supir.index', compact('data_feedback'));
    }

    public function exportPdfSupir(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $tipe = $request->input('tipe');

        $query = FeedbackSupir::orderBy('id', 'desc');

        if ($startDate && $endDate) {
            $startDate = Carbon::parse($startDate)->startOfDay(); // 2024-02-27 00:00:00
            $endDate = Carbon::parse($endDate)->endOfDay(); // 2024-02-27 23:59:59

            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        if ($tipe) {
            $query->where('tipe', $tipe);
        }

        $data_feedback = $query->get();

        $pdf = PDF::loadView('feedback_supir.pdf', compact('data_feedback'));
        return $pdf->stream('feedback_supir_report.pdf');
    }
}
