<?php

namespace App\Http\Controllers\Loja;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('loja.home');
    }
    public function privacy()
    {
        return view('loja.privacy-policies');
    }
}
