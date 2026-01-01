<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\Employee\CreateEmployeeRequest;
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

        return ResponseHelper::success('Department data has been successfully retrieved', EmployeeResource::collection($data));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateEmployeeRequest $request)
    {
        $employee = Employee::create($request->validated());

        $employee->load(['department', 'position', 'user']);

        return ResponseHelper::success('New employee has been successfully added', new EmployeeResource($employee)
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        //
    }
}
