<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\Position\CreatePositionRequest;
use App\Http\Requests\Position\UpdatePositionRequest;
use App\Http\Resources\PositionResource;
use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Position::all();

        if ($data->isEmpty()) {
            return ResponseHelper::success('There is no position data. Please add position.',);
        }

        return ResponseHelper::success('Position data has been successfully retrieved', PositionResource::collection($data));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreatePositionRequest $request)
    {
        $data = $request->validated();

        $position = Position::create($data);

        return ResponseHelper::success('New position has been successfully added', new PositionResource($position));
    }

    /**
     * Display the specified resource.
     */
    public function show(Position $position)
    {
        return ResponseHelper::success('Position detail retrieved successfully', new PositionResource($position));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePositionRequest $request, Position $position)
    {
        $data = $request->validated();

        $position->update($data);

        return ResponseHelper::success('Position has been successfully updated', new PositionResource($position));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Position $position)
    {
        $position->delete();

        return ResponseHelper::success('Position has been successfully deleted');
    }
}
