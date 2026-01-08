<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\Employee\CreateEmployeeRequest;
use App\Http\Requests\Employee\UpdateEmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Employee::with(['department', 'position', 'user'])
            ->orderBy('employee_code')
            ->get();

        if ($data->isEmpty()) {
            return ResponseHelper::success('There is no employee data. Please add employee.',);
        }

        return ResponseHelper::success('Employee data has been successfully retrieved', EmployeeResource::collection($data));
    }

    public function getByOwner(Request $request)
    {
        $userId = auth()->id();

        $employees = Employee::with(['department', 'position', 'user'])
            ->where('user_id', $userId)
            ->orderBy('employee_code')
            ->get();

        if ($employees->isEmpty()) {
            return ResponseHelper::success('You have no employee data yet.');
        }

        return ResponseHelper::success('Your employee data has been successfully retrieved', EmployeeResource::collection($employees));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateEmployeeRequest $request)
    {
        $employee = Employee::create($request->validated());

        $employee->load(['department', 'position', 'user']);

        return ResponseHelper::success('New employee has been successfully added', new EmployeeResource($employee));
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        $employee->load(['department', 'position', 'user']);

        return ResponseHelper::success('Employee detail retrieved successfully', new EmployeeResource($employee));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        $data = $request->validated();

        $employee->update($data);

        return ResponseHelper::success('Employee has been successfully updated', new EmployeeResource($employee));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        if ($employee->user) {
            $employee->user->delete();
        }

        $employee->delete();

        return ResponseHelper::success('Employee has been successfully deleted');
    }
}
