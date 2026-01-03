<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    protected $table = 'departments';

    protected $fillable = [
        'code',
        'name',
        'manager_id'
    ];

    protected static function booted()
    {
        static::creating(function ($department) {
            if (empty($department->code)) {
                $department->code = static::generateCode();
            }
        });
    }

    protected static function generateCode(): string
    {
        $lastNumber = static::query()
            ->where('code', 'like', 'DEPT-%')
            ->selectRaw("CAST(SUBSTRING(code, 6) AS INTEGER) as num")
            ->orderByDesc('num')
            ->value('num');

        $nextNumber = ($lastNumber ?? 0) + 1;

        return 'DEPT-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}
