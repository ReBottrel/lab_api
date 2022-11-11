<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ConfigController extends Controller
{
    public function index()
    {
        $admins = Admin::get();
        return view('admin.configs.index', get_defined_vars());
    }
    public function adminEdit($id)
    {
        $admin = Admin::find($id);
        return view('admin.configs.admins.edit', get_defined_vars());
    }
}
