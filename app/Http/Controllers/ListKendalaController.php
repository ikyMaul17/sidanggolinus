<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Kendala;
use PDF;

class ListKendalaController extends Controller
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
    public function index()
    {
       $query = Kendala::orderBy('id', 'desc');
       $data_kendala = $query->get();

      return view('kendala.index', compact('data_kendala'));
    }
}
