<?php

namespace App\Http\Controllers\MainPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class businessController extends Controller
{
   public function business(){
    return view('mainpage.business');
   }
}
