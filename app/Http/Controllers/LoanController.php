<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        return view('loans.index');
    }

    public function borrow(Request $request)
    {
        return view('loans.borrow');
    }

    public function return(Request $request)
    {
        return view('loans.return');
    }
}
