<?php

namespace App\Http\Controllers\MainPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;

class knowmeController extends Controller
{
    //
    public function index(string $public_id) {
        $employee = Employee::where('public_id', $public_id)
            ->firstOrFail();

        return view('mainpage.knowme', compact('employee'));
    }
}
