<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'Super Admin', 'description' => 'Has full access to all system features.'],
            ['name' => 'Operations Manager', 'description' => 'Oversees operations and workflow management.'],
            ['name' => 'Compliance Officer', 'description' => 'Handles regulatory compliance and KYC/AML.'],
            ['name' => 'Customer Support Lead', 'description' => 'Manages customer service and support team.'],
            ['name' => 'Finance Admin', 'description' => 'Manages payments, reconciliations, and finance reporting.'],
            ['name' => 'Marketing Admin', 'description' => 'Oversees campaigns, social media, and digital marketing.'],
            ['name' => 'Product Manager', 'description' => 'Manages product roadmap and features.'],
            ['name' => 'Risk Analyst', 'description' => 'Monitors financial risks and transactions.'],
            ['name' => 'Security Admin', 'description' => 'Manages system security and user permissions.'],
            ['name' => 'IT Support', 'description' => 'Handles technical issues and system maintenance.'],
            ['name' => 'Graphic Designer', 'description' => 'Creates visual content, UI/UX designs, and graphics for campaigns.'],
        ];

        DB::table('admin_roles')->insert($roles);
    }
}
