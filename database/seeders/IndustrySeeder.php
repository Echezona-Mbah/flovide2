<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IndustrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $industries = [
            'Agriculture',
            'Automotive',
            'Banking & Finance',
            'Construction',
            'Education',
            'Energy',
            'Entertainment',
            'Fashion',
            'Food & Beverage',
            'Healthcare',
            'Hospitality',
            'Information Technology',
            'Legal',
            'Logistics',
            'Manufacturing',
            'Media',
            'Mining',
            'Oil & Gas',
            'Pharmaceuticals',
            'Real Estate',
            'Retail',
            'Telecommunications',
            'Textiles',
            'Transportation',
            'Utilities'
        ];

        $records = array_map(fn($name) => [
            'name' => $name,
            'created_at' => now(),
            'updated_at' => now()
        ], $industries);

        DB::table('industries')->insert($records);
    }
}
