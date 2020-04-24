<?php

namespace App\Http\Controllers;

use App\Kios;
use App\LoanArrea;
use App\Sales;
use App\Tenor;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:administrator');
    }

    public function index()
    {
        $controller    = new Controller;
        $data['menus'] = $controller->menus();

        

        return view('dashboard.dashboard', $data);
    }

    
}
