<?php

namespace App\Http\Controllers\User;

use App\Models\OrderRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index()
    {
        $prices = [55.00, 500.00, 400.00, 300.00, 300.00, 200.00];
        $user = Auth::user()->id;
        $orders = OrderRequest::with('user', 'orderRequestPayment')->where('user_id', 4)->get();
        // dd($orders);
        return view('user.dashboard', get_defined_vars());
    }
}
