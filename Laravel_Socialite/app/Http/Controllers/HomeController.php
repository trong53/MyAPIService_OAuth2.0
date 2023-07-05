<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $this->middleware(['auth']);

        $this->middleware(['verified']);
       

        // khong the nao lay ra dc authenticated user o day
        // Mac du da thong qua middleware 'auth' roi, nhung
        // Auth::user()  hay $request->user() deu return null

        // tao ra middleware 'local.user' (trong kernel)
        // va php artisan make:middleware LocalUser : cho phep Local User di qua
        // ben trong class LocalUser thi bay gio ta co the lay ra authenticated $user()
        // nhung middleware tra ve RedirectResponse, ko tra ve Boolean dc

        // Tao ra Helper functions => khong the lay ra User

        // Dieu chinh lai conditions trong middleware 'verified' = class EnsureEmailIsVerified
        // => it works
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // dd(Auth::user());            // get authenticated user
        // dd(checkAuthUser());         // get authenticated user
        return view('home');
    }
}
