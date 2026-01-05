<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function getManagers()
    {
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user || !in_array($user->role, ['admin', 'hr'])) {
            return ResponseHelper::apiError('Unauthorized', null, 403);
        }

        $managers = User::where('role', 'manager')->get();

        return ResponseHelper::success(
            'Managers fetched successfully',
            UserResource::collection($managers)
        );
    }
}
