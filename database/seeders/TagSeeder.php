<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            'Fintech',
            'Digital Banking',
            'Payments',
            'API',
            'Security',
            'Fraud Prevention',
            'Mobile Money',
            'Open Banking',
            'Financial Inclusion',
            'Nigeria',
            'Africa',
        ];

        foreach ($tags as $tag) {
            Tag::firstOrCreate(
                ['slug' => Str::slug($tag)],
                [
                    'name' => $tag,
                ]
            );
        }
    }
}