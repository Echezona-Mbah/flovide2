<?php

namespace App\Http\Controllers\MainPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class personalController extends Controller
{
    public function personal(){
        return view('mainpage.personal');
       }
}
