<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscriber;
use App\User;
use App\Models\InstantMessage;
use App\Models\Campaign;

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
        $subscribers = Subscriber::count();
        $users = User::count();
        $instantMessages = InstantMessage::count();
        $campaigns = Campaign::count();
        return view('admin.admin', compact('subscribers', 'users', 'instantMessages','campaigns'));
    }
}
