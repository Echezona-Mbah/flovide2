<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index() {
        return view('admin.blog');
    }
    public function store(Request $request)
    {
    }


    public function update(Request $request, $id)
    {
    }


    public function destroy($id)
    {

        
    }
}
