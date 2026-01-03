<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return ResponseHelper::apiError('Email or password is incorrect', null, 401);
        }

        $token = JWTAuth::fromUser($user);

        return ResponseHelper::success('Login successful', [
            'user' => new UserResource($user),
            'token' => $token,
        ]);
    }

    public function loginWithEmployeeCode(Request $request)
    {
        if (!$request->employee_code) {
            return ResponseHelper::apiError('employee_code is required', null, 422);
        }

        $employee = Employee::with('user')
            ->where('employee_code', $request->employee_code)
            ->first();

        if (!$employee || !$employee->user) {
            return ResponseHelper::apiError('Employee account not found', null, 401);
        }

        $token = JWTAuth::fromUser($employee->user);

        return ResponseHelper::success('Employee login successful', [
            'user' => new UserResource($employee->user),
            'token' => $token,
        ]);
    }

    public function profile()
    {
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return ResponseHelper::apiError('User profile not found', null, 404);
        }

        return ResponseHelper::success('User profile fetched', new UserResource($user));
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $data = $request->validated();

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return ResponseHelper::success('User profile updated', new UserResource($user));
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return ResponseHelper::success('Logout successful');
    }
}
