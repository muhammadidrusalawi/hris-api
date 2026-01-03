<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\Department\CreateDepartmentRequest;
use App\Http\Requests\Department\UpdateDepartmentRequest;
use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Department::with('manager')->withCount('employees')->get();

        if ($data->isEmpty()) {
            return ResponseHelper::success('There is no department data. Please add department.',);
        }

        return ResponseHelper::success('Department data has been successfully retrieved', DepartmentResource::collection($data));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateDepartmentRequest $request)
    {
        $data = $request->validated();

        $department = Department::create($data);

        return ResponseHelper::success('New department has been successfully added', new DepartmentResource($department));
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        $department->load(['manager', 'employees'])->loadCount('employees');

        return ResponseHelper::success('Department detail retrieved successfully', new DepartmentResource($department));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDepartmentRequest $request, Department $department)
    {
        $data = $request->validated();

        $department->update($data);

        return ResponseHelper::success('Department has been successfully updated', new DepartmentResource($department));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        $department->delete();

        return ResponseHelper::success('Department has been successfully deleted');
    }
}
