<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        $user = Auth()->user();


   if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'housekeeping') {
            return redirect()->route('housekeeping.dashboard');
        } elseif ($user->role === 'resepsionis') {
            return redirect()->route('resepsionis.dashboard');
        }
          return view('home'); // fallback
    }
}
