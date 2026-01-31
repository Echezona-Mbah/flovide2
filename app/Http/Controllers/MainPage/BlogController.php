<?php

namespace App\Http\Controllers\MainPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        return view('mainpage.blog');
    }
}
