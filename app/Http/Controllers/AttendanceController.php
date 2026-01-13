<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\Attendance\CreateAttendanceRequest;
use App\Http\Requests\Employee\UpdateEmployeeRequest;
use App\Http\Resources\AttendanceResource;
use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Attendance::with('employee')->get();

        return ResponseHelper::success("Employee data has been successfully retrieved", AttendanceResource::collection($data));
    }

    public function getAttendanceFromEmployee()
    {
        $user = auth()->user();
        $employee = $user->employee;

        if (!$employee) {
            return ResponseHelper::apiError("Employee record not found for this user", null, 404);
        }

        $attendances = Attendance::where('employee_id', $employee->id)
            ->with('employee')
            ->orderBy('date', 'desc')
            ->get();

        return ResponseHelper::success("Employee data has been successfully retrieved", AttendanceResource::collection($attendances));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateAttendanceRequest $request)
    {
        //
    }

    public function storeAttendanceFromEmployee(CreateAttendanceRequest $request)
    {
        $user = auth()->user();
        $employee = $user->employee;

        if (!$employee) {
            return ResponseHelper::apiError("Employee record not found for this user", null, 404);
        }

        $todayAttendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', now()->toDateString())
            ->first();

        if ($todayAttendance) {
            return ResponseHelper::apiError("You already checked in today", null, 409);
        }

        $data = $request->validated();
        $data['employee_id'] = $employee->id;
        $data['clock_in'] = $data['clock_in'] ?? now();
        $data['date'] = $data['date'] ?? now()->toDateString();

        $attendance = Attendance::create($data);

        return ResponseHelper::success('Check-in successful', new AttendanceResource($attendance));
    }

    public function updateFromEmployee(UpdateEmployeeRequest $request, Attendance $attendance)
    {
        $user = auth()->user();
        $employee = $user->employee;

        if (!$employee) {
            return ResponseHelper::apiError("Employee record not found for this user",404);
        }

        if ($attendance->employee_id !== $employee->id) {
            return ResponseHelper::apiError("You cannot update this attendance", 403);
        }

        if (!$attendance->clock_in) {
            return ResponseHelper::apiError("Cannot check out without checking in first", 400);
        }

        $clockOutTime = $request->input('clock_out'); // "HH:mm:ss"
        $clockOutDateTime = $attendance->date->format('Y-m-d') . ' ' . $clockOutTime;

        $attendance->clock_out = $clockOutDateTime;
        $attendance->save();

        $attendance->refresh();

        return ResponseHelper::success('Checked out successful', new AttendanceResource($attendance));
    }

    /**
     * Display the specified resource.
     */
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendance $attendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        //
    }
}
