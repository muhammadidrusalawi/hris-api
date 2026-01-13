<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\Leave\CreateLeaveRequest;
use App\Http\Resources\LeaveResource;
use App\Models\Leave;
use App\Models\LeaveBalance;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $employee = auth()->user()->employee;
        $year = $request->query('year', now()->year);

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee data not found for this user',
            ], 404);
        }

        $leaves = Leave::with('employee')
            ->where('employee_id', $employee->id)
            ->whereYear('start_date', $year) // filter by year
            ->orderBy('start_date')
            ->get();

        return ResponseHelper::success(
            "Your leave records successfully retrieved",
            LeaveResource::collection($leaves)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateLeaveRequest $request)
    {
        $employee = auth()->user()->employee;

        // hitung total_days otomatis
        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);
        $totalDays = $start->diffInDays($end) + 1;

        // ambil leave balance tahun yang sesuai
        $leaveBalance = LeaveBalance::where('employee_id', $employee->id)
            ->where('year', $start->year)
            ->first();

        if (!$leaveBalance) {
            return response()->json([
                'success' => false,
                'message' => 'Leave balance for this year not found',
            ], 422);
        }

        // cek kuota
        if ($leaveBalance->remaining_quota < $totalDays) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient leave quota',
                'remaining_quota' => $leaveBalance->remaining_quota,
                'requested_days' => $totalDays,
            ], 422);
        }

        // create leave
        $leave = Leave::create([
            'employee_id' => $employee->id,
            'type' => $request->type,
            'start_date' => $start,
            'end_date' => $end,
            'total_days' => $totalDays,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Leave request created successfully',
            'data' => new LeaveResource($leave),
        ]);
    }

    public function approve(Request $request, Leave $leave)
    {
        $user = Auth::user();

        if ($leave->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending leaves can be approved',
            ], 422);
        }

        $leaveBalance = LeaveBalance::where('employee_id', $leave->employee_id)
            ->where('year', $leave->start_date->year)
            ->first();

        if (!$leaveBalance) {
            return response()->json([
                'success' => false,
                'message' => 'Leave balance for this year not found',
            ], 422);
        }

        if ($leaveBalance->remaining_quota < $leave->total_days) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient leave quota',
                'remaining_quota' => $leaveBalance->remaining_quota,
                'requested_days' => $leave->total_days,
            ], 422);
        }

        $leave->status = 'approved';
        $leave->approved_by = $user->id;
        $leave->save();

        $leaveBalance->used_quota += $leave->total_days;
        $leaveBalance->remaining_quota -= $leave->total_days;
        $leaveBalance->save();

        return ResponseHelper::success("Leave approved successfully", new LeaveResource($leave));
    }

    /**
     * Display the specified resource.
     */
    public function show(Leave $leave)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Leave $leave)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Leave $leave)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Leave $leave)
    {
        //
    }
}
