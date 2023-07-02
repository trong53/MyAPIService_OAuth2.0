<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Passport\Http\Controllers\ClientController;
use Laravel\Passport\Client;
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
        return view('home');
    }

    public function clientsList()
    {
        // Get authenticated user
        $user = Auth::user();

        // get all his actif clients
        /** @var \app\models\User $user */
        $clients = $user->clients()->where('revoked', 0)->orderBy('updated_at', 'desc')->get();

        return view('clients.list', compact('clients'));
    }

    public function createForm()
    {
        return view('clients.create');
    }

    public function editForm($id)
    {
        $client = Client::where('id', $id)->first();

        return view('clients.edit', compact('client'));
    }
}
