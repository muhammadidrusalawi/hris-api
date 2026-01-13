<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeaveBalanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $balance = $this->leaveBalances->first();

        return [
            'employee' => [
                'id'   => $this->id,
                'code' => $this->employee_code,
                'name' => $this->name,
                'department' => $this->department?->name,
                'position' => $this->position?->name,
            ],

            'leave_balance' => $balance ? [
                'year'      => $balance->year,
                'total'     => $balance->total_quota,
                'used'      => $balance->used_quota,
                'remaining' => $balance->remaining_quota,
            ] : null,

            'pending_leaves' => $this->leaves->count(),
        ];
    }
}
