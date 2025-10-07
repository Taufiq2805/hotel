<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

   protected function authenticated($request, $user)
   {
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'housekeeping') {
            return redirect()->route('housekeeping.dashboard');
        } elseif ($user->role === 'resepsionis') {
            return redirect()->route('resepsionis.dashboard');
        }
      
       return redirect('/home'); // default
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}