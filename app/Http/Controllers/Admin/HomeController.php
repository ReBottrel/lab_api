<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderRequest;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $orders = OrderRequest::get();
        return view('admin.index', get_defined_vars());
    }
}
