<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Company News',
            'FinTech & Innovation',
            'Product Updates',
            'Developer Tutorials',
            'Financial Literacy',
            'Security & Fraud Prevention',
            'Digital Banking',
            'Compliance & Regulations',
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['slug' => Str::slug($category)],
                [
                    'name' => $category,
                ]
            );
        }
    }
}