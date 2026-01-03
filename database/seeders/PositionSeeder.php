<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = [

            // Executive
            'Chief Executive Officer',
            'Chief Technology Officer',
            'Chief Financial Officer',
            'Chief Operating Officer',

            // Management
            'Engineering Manager',
            'Product Manager',
            'Project Manager',
            'HR Manager',
            'Finance Manager',
            'Marketing Manager',
            'Sales Manager',

            // Tech – Engineering
            'Backend Developer',
            'Frontend Developer',
            'Fullstack Developer',
            'Mobile Developer',
            'DevOps Engineer',
            'Site Reliability Engineer',
            'Software Engineer',
            'QA Engineer',
            'Automation Engineer',
            'Data Engineer',
            'Data Scientist',
            'Machine Learning Engineer',

            // Tech – Infrastructure
            'System Administrator',
            'Network Engineer',
            'Cloud Engineer',
            'Security Engineer',

            // Product & Design
            'Product Owner',
            'UI/UX Designer',
            'UX Researcher',

            // Business & Operations
            'Business Analyst',
            'Operations Officer',
            'Procurement Officer',
            'Compliance Officer',

            // Finance & Accounting
            'Accountant',
            'Finance Analyst',
            'Payroll Officer',
            'Tax Officer',

            // HR
            'HR Officer',
            'Talent Acquisition',
            'People Development Specialist',

            // Marketing
            'Digital Marketing Specialist',
            'Content Strategist',
            'SEO Specialist',
            'Brand Manager',

            // Sales
            'Sales Executive',
            'Account Executive',
            'Business Development Officer',

            // Support
            'Customer Support',
            'Technical Support',
            'IT Support',

            // Entry / General
            'Senior Staff',
            'Staff',
            'Junior Staff',
            'Intern',
        ];

        foreach ($positions as $name) {
            Position::updateOrCreate(
                ['name' => $name]
            );
        }
    }
}
