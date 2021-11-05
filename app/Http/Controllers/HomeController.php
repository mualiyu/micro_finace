<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ebulksms;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $transactions = Transaction::where('from', '=', Auth::user()->account)->orWhere('to', '=', Auth::user()->account)->orderBy('created_at', 'desc')->get();

        return view('home', compact('transactions'));
    }
}
