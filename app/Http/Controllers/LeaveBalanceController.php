<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Resources\LeaveBalanceResource;
use App\Http\Resources\LeaveResource;
use App\Models\Employee;
use App\Models\LeaveBalance;
use Illuminate\Http\Request;

class LeaveBalanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $year = $request->query('year', now()->year);

        $data = Employee::with([
            'department',
            'position',
            'leaveBalances' => fn ($q) => $q->where('year', $year),
            'leaves' => fn ($q) => $q
                ->whereYear('start_date', $year)
                ->where('status', 'pending'),
        ])
            ->orderBy('employee_code')
            ->get();

        return ResponseHelper::success("Leave balance data has been successfully retrieved", LeaveBalanceResource::collection($data));
    }

    public function myLeaveBalance(Request $request)
    {
        $year = $request->query('year', now()->year);

        $employee = Employee::with([
            'department',
            'position',
            'leaveBalances' => fn ($q) => $q->where('year', $year),
            'leaves' => fn ($q) => $q
                ->whereYear('start_date', $year)
                ->where('status', 'pending'),
        ])
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return ResponseHelper::success("Your leave balance data has been successfully retrieved", new LeaveBalanceResource($employee));
    }

    /**
     * Display the specified resource.
     */
    public function show($employeeId, Request $request)
    {
        $year = $request->query('year', now()->year);

        $employee = Employee::with([
            'leaves' => fn($q) => $q->whereYear('start_date', $year),
        ])->findOrFail($employeeId);

        return ResponseHelper::success("Leaves for employee retrieved successfully", LeaveResource::collection($employee->leaves));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LeaveBalance $leaveBalance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LeaveBalance $leaveBalance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LeaveBalance $leaveBalance)
    {
        //
    }
}
