<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'employee_code' => $this->employee_code,
            'name' => $this->name,

            'department' => [
                'id' => $this->department?->id,
                'name' => $this->department?->name,
            ],

            'position' => [
                'id' => $this->position?->id,
                'name' => $this->position?->name,
            ],

            'user' => $this->whenLoaded('user', fn () => [
                'id' => $this->user->id,
                'email' => $this->user->email,
                'role' => $this->user->role,
            ]),

            'join_date' => $this->join_date,
            'status' => $this->status,

            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
