<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
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
            'employee_id' => $this->employee_id,
            'employee_code' => $this->employee ? $this->employee->employee_code : null,
            'employee_name' => $this->employee ? $this->employee->name : null,
            'position' => $this->employee ? $this->employee->position->name : null,
            'date' => $this->date->format('Y-m-d'),
            'clock_in' => $this->clock_in ? $this->clock_in->format('H:i:s') : null,
            'clock_out' => $this->clock_out,
        ];
    }
}
