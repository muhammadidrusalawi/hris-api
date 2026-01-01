<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'employee_code',
        'name',
        'department_id',
        'position_id',
        'join_date',
        'status'
    ];

    protected static function booted()
    {
        static::creating(function ($employee) {
            if (empty($employee->id)) {
                $employee->id = (string) Str::uuid();
            }

            if (!empty($employee->employee_code)) return;

            $year = now()->year;

            $lastCode = DB::table('employees')
                ->where('employee_code', 'like', "$year%")
                ->orderByDesc('employee_code')
                ->value('employee_code');

            $next = $lastCode
                ? ((int) substr($lastCode, -4) + 1)
                : 1;

            $employee->employee_code = $year . str_pad($next, 4, '0', STR_PAD_LEFT);
        });

        static::created(function ($employee) {
            if ($employee->user_id !== null) return;

            $email = self::generateEmployeeEmail($employee);

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $employee->name,
                    'password' => Hash::make(Str::random(12)),
                    'role' => 'employee',
                ]
            );

            if ($employee->user_id !== $user->id) {
                $employee->update([
                    'user_id' => $user->id,
                ]);
            }
        });

    }

    private static function generateEmployeeEmail($employee): string
    {
        // default dummy email (HRIS standard)
        return strtolower($employee->employee_code) . '@company.local';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }
}
