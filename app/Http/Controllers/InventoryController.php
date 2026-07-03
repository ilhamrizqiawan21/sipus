<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        return view('inventory.index');
    }

    public function procurement(Request $request)
    {
        return view('inventory.procurement');
    }
}
