<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Throwable;
use Illuminate\Support\Facades\DB;

class knowmeController extends Controller
{
    public function index()
    {
        $employees = Employee::all();
        return view('admin.knowme', compact('employees'));
    }

    public function create(Request $request)
    {

        $admin = Auth::guard('admin')->user();

        if (!$admin) {
            return redirect()->route('admin.login')->with('error', 'Please login first');
        }

        $adminId = $admin->getAuthIdentifier();
        $photoPath = null;

        // Normalize inputs before validation.
        $request->merge([
            'name' => is_string($request->input('name'))
                ? trim($request->input('name'))
                : $request->input('name'),

            'employee_id' => is_string($request->input('employee_id'))
                ? strtoupper(trim($request->input('employee_id')))
                : $request->input('employee_id'),

            'job_title' => is_string($request->input('job_title'))
                ? trim($request->input('job_title'))
                : $request->input('job_title'),

            'department' => is_string($request->input('department'))
                ? trim($request->input('department'))
                : $request->input('department'),

            'work_email' => is_string($request->input('work_email'))
                ? Str::lower(trim($request->input('work_email')))
                : $request->input('work_email'),
        ]);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'employee_id' => [
                'required',
                'string',
                'max:50',
                'regex:/^[A-Z0-9-]+$/',
                'unique:employees,employee_id',
            ],

            'job_title' => [
                'required',
                'string',
                'max:255',
            ],

            'department' => [
                'required',
                'string',
                'max:255',
            ],

            'work_email' => [
                'required',
                'email',
                'max:255',
                'unique:employees,work_email',
            ],

            'employment_date' => [
                'required',
                'date_format:Y-m-d',
                'before_or_equal:today',
            ],

            'id_card_expiration_date' => [
                'required',
                'date_format:Y-m-d',
                'after:today',
            ],

            'status' => [
                'required',
                Rule::in(['active', 'inactive', 'on_leave']),
            ],

            'photo' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
                'dimensions:min_width=200,min_height=200',
            ],
            [
                'photo.required' => 'Please upload an employee photo.',
                'photo.image' => 'The uploaded file must be a valid image.',
                'photo.mimes' => 'The employee photo must be JPG, JPEG, PNG, or WEBP format.',
                'photo.max' => 'The employee photo must not be larger than 5 MB. Please choose a smaller image.',
                'photo.dimensions' => 'The employee photo must be at least 200 × 200 pixels.',
            ]
        ]);

        try {
            // Store the image on Laravel's public disk.
            $photoPath = $request->file('photo')
                ->store('employee-photos', 'public');

            if (!$photoPath) {
                Log::error('Employee photo upload failed.', [
                    'admin_id' => $adminId,
                    'reason' => 'Storage returned no file path.',
                ]);

                return back()
                    ->withInput()
                    ->with('error', 'Unable to upload the employee photo. Please try again.');
            }
            $employee = DB::transaction(function () use ($validated, $photoPath) {
                return Employee::create([
                    'name' => $validated['name'],
                    'employee_id' => $validated['employee_id'],
                    'job_title' => $validated['job_title'],
                    'department' => $validated['department'],
                    'work_email' => $validated['work_email'],
                    'employment_date' => $validated['employment_date'],
                    'id_card_expiration_date' => $validated['id_card_expiration_date'],
                    'status' => $validated['status'],
                    'photo_path' => $photoPath,
                ]);
            });

            Log::info('Employee created successfully.', [
                'employee_record_id' => $employee->id,
                'employee_id' => $employee->employee_id,
                'admin_id' => $adminId,
                'status' => $employee->status,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Employee added successfully.',
                'employee' => [
                    'id' => $employee->employee_id,
                    'public_id' => $employee->public_id,
                ],
            ], 201);

        } catch (Throwable $e) {
            // Remove the uploaded file if saving the employee fails.
            if ($photoPath !== null) {
                try {
                    Storage::disk('public')->delete($photoPath);
                } catch (Throwable $cleanupException) {
                    Log::warning('Failed to clean up employee photo after an error.', [
                        'admin_id' => $adminId,
                        'exception_class' => get_class($cleanupException),
                    ]);
                }
            }

            Log::error('Failed to create employee record.', [
                'admin_id' => $adminId,
                'exception_class' => get_class($e),
                'exception_message' => $e->getMessage(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unable to save employee details right now. Please try again.',
                ], 500);
            }

            return back()
                ->withInput()
                ->with('error', 'Unable to save employee details right now. Please try again.');
        }
    }

    public function update(Request $request, Employee $employee)
    {
        $admin = Auth::guard('admin')->user();

        if (!$admin) {
            return response()->json(['success' => false, 'message' => 'Please login first.'], 401);
        }

        $adminId = $admin->getAuthIdentifier();

        // Normalize inputs
        $request->merge([
            'name' => is_string($request->input('name')) ? trim($request->input('name')) : $request->input('name'),
            'job_title' => is_string($request->input('job_title')) ? trim($request->input('job_title')) : $request->input('job_title'),
            'department' => is_string($request->input('department')) ? trim($request->input('department')) : $request->input('department'),
            'work_email' => is_string($request->input('work_email')) ? Str::lower(trim($request->input('work_email'))) : $request->input('work_email'),
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'job_title' => ['required', 'string', 'max:255'],
            'department' => ['required', 'string', 'max:255'],
            'work_email' => ['required', 'email', 'max:255', Rule::unique('employees', 'work_email')->ignore($employee->id)],
            'id_card_expiration_date' => ['required', 'date_format:Y-m-d', 'after:today'],
            'status' => ['required', Rule::in(['active', 'inactive', 'on_leave'])],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120', 'dimensions:min_width=200,min_height=200'],
        ]);

        try {
            DB::transaction(function () use ($request, $validated, $employee, $adminId) {
                $data = [
                    'name' => $validated['name'],
                    'job_title' => $validated['job_title'],
                    'department' => $validated['department'],
                    'work_email' => $validated['work_email'],
                    'id_card_expiration_date' => $validated['id_card_expiration_date'],
                    'status' => $validated['status'],
                ];

                if ($request->hasFile('photo')) {
                    // Delete old photo
                    if ($employee->photo_path) {
                        Storage::disk('public')->delete($employee->photo_path);
                    }
                    $data['photo_path'] = $request->file('photo')->store('employee-photos', 'public');
                }

                $employee->update($data);

                Log::info('Employee updated successfully.', [
                    'employee_record_id' => $employee->id,
                    'admin_id' => $adminId,
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Employee updated successfully.',
                'employee' => [
                    'id' => $employee->employee_id,
                    'name' => $employee->name,
                    'job_title' => $employee->job_title,
                    'department' => $employee->department,
                    'work_email' => $employee->work_email,
                    'status' => $employee->status,
                    'expiration' => $employee->id_card_expiration_date
                        ? \Carbon\Carbon::parse($employee->id_card_expiration_date)->format('d M Y')
                        : '—',
                    'photo_url'  => $employee->photo_path ? asset('storage/' . $employee->photo_path) : null,
                ],
            ]);

        } catch (Throwable $e) {
            Log::error('Failed to update employee record.', [
                'employee_record_id' => $employee->id,
                'admin_id' => $adminId,
                'exception_message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to update employee right now. Please try again.',
            ], 500);
        }
    }




    public function destroy(Employee $employee)
    {
        $admin = Auth::guard('admin')->user();

        if (!$admin) {
            return response()->json([
                'success' => false,
                'message' => 'Please login first.',
            ], 401);
        }

        $adminId = $admin->getAuthIdentifier();

        try {
            DB::transaction(function () use ($employee, $adminId) {
                // Soft delete the employee record.
                $employee->delete();

                Log::info('Employee soft-deleted successfully.', [
                    'employee_record_id' => $employee->id,
                    'employee_id' => $employee->employee_id,
                    'admin_id' => $adminId,
                    'deleted_at' => $employee->deleted_at,
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Employee deleted successfully.',
            ], 200);

        } catch (Throwable $e) {
            Log::error('Failed to soft-delete employee record.', [
                'employee_record_id' => $employee->id,
                'employee_id' => $employee->employee_id,
                'admin_id' => $adminId,
                'exception_class' => get_class($e),
                'exception_message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to delete employee right now. Please try again.',
            ], 500);
        }
    }

}
