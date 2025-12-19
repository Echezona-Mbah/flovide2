<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Career;
use Illuminate\Http\Request;

class CareerController extends Controller
{
     public function index()
    {
        $jobs = Career::latest()->paginate(10);
        return view('admin.careerview', compact('jobs'));
    }

    public function create()
    {
        return view('admin.career');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'job_type' => 'required',
            'location' => 'required',
            'description' => 'required',
            'requirements' => 'required',
        ]);

        Career::create([
            'title' => $request->title,
            'job_type' => $request->job_type,
            'location' => $request->location,
            'description' => $request->description,
            'requirements' => $request->requirements,
            'status' => $request->status ?? 1,
        ]);

        return redirect()->route('admin.career.index')->with('success', 'Job added successfully!');
    }

    public function edit($id)
    {
        $job = Career::findOrFail($id);
        return view('admin.career-edit', compact('job'));
    }


    public function update(Request $request, $id)
{
    $job = Career::findOrFail($id);

    $job->update([
        'title'        => $request->title,
        'job_type'     => $request->job_type,
        'location'     => $request->location,
        'description'  => $request->description,
        'requirements' => $request->requirements,
        'status'       => $request->status,
    ]);

    return redirect()->route('admin.career.index')->with('success', 'Career updated successfully.');
}


    public function destroy($id)
{
    $job = Career::findOrFail($id);
    $job->delete();

    return redirect()->route('admin.career.index')->with('success', 'career deleted successfully.');
}



}
