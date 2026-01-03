<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'    => $this->id,
            'code'  => $this->code,
            'name'  => $this->name,
            'manager' => $this->whenLoaded('manager', fn () => [
                'id' => $this->manager->id,
                'name' => $this->manager->name,
            ]),
            'employee_count' => $this->whenCounted('employees'),
            'employees' => $this->whenLoaded('employees', function () {
                return $this->employees->map(fn ($employee) => [
                    'id' => $employee->id,
                    'employee_code' => $employee->employee_code,
                    'name' => $employee->name,
                    'position' => [
                        'name' => $employee->position->name,
                    ],
                ]);
            }),
        ];
    }
}
