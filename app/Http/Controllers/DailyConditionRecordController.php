<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DailyConditionRecordController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'semua');

        $inspectionsQuery = DB::table('daily_inspections')
            ->join('bus', 'daily_inspections.id_bus', '=', 'bus.id')
            ->join('users as supir', 'daily_inspections.id_supir', '=', 'supir.id')
            ->select(
                'daily_inspections.*',
                'bus.nama as nama_bus',
                'supir.nama as nama_supir'
            )
            ->orderBy('daily_inspections.inspected_at', 'desc');

        // Apply filter
        if ($filter === 'rusak') {
            // Only show inspections that have at least one Rusak item
            $inspectionsQuery->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('daily_inspection_results')
                    ->whereColumn('daily_inspection_results.daily_inspection_id', 'daily_inspections.id')
                    ->where('daily_inspection_results.status', 2);
            });
        } elseif ($filter === 'baik') {
            // Only show inspections where all items are Baik (no Rusak items)
            $inspectionsQuery->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('daily_inspection_results')
                    ->whereColumn('daily_inspection_results.daily_inspection_id', 'daily_inspections.id')
                    ->where('daily_inspection_results.status', 2);
            });
        }

        $inspections = $inspectionsQuery->get();

        $results = DB::table('daily_inspection_results')
            ->join('inspection_items', 'daily_inspection_results.inspection_item_id', '=', 'inspection_items.id')
            ->select(
                'daily_inspection_results.daily_inspection_id',
                'daily_inspection_results.status',
                'daily_inspection_results.keterangan_rusak',
                'inspection_items.nama'
            )
            ->orderBy('inspection_items.nama', 'asc')
            ->get()
            ->groupBy('daily_inspection_id');

        return view('daily_condition_records.index', [
            'inspections' => $inspections,
            'resultsByInspection' => $results,
            'filter' => $filter,
        ]);
    }
}
