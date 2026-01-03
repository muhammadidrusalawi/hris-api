<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['dept' => 'Human Resources',        'name' => 'Andi Pratama'],
            ['dept' => 'Finance',               'name' => 'Budi Santoso'],
            ['dept' => 'Accounting',            'name' => 'Citra Lestari'],
            ['dept' => 'Information Technology','name' => 'Dimas Saputra'],
            ['dept' => 'Operations',            'name' => 'Eko Wibowo'],
            ['dept' => 'Sales',                 'name' => 'Farhan Hidayat'],
            ['dept' => 'Marketing',             'name' => 'Gita Maharani'],
            ['dept' => 'General Affairs',       'name' => 'Hendra Gunawan'],
            ['dept' => 'Procurement',           'name' => 'Intan Permata'],
            ['dept' => 'Customer Service',      'name' => 'Joko Susilo'],
        ];

        foreach ($data as $index => $item) {
            $email = Str::of($item['name'])
                ->lower()
                ->replace(' ', '.')
                ->append('@company.local')
                ->toString();

            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'id'       => Str::uuid(),
                    'name'     => $item['name'],
                    'role'     => 'manager',
                    'password' => Hash::make('12345678'),
                ]
            );

            Department::withoutEvents(function () use ($item, $user, $index) {
                Department::updateOrCreate(
                    ['name' => $item['dept']],
                    [
                        'manager_id' => $user->id,
                        'code'       => 'DEPT-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                    ]
                );
            });
        }
    }
}
