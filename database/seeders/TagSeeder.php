<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagSeeder extends Seeder
{
    public function run()
    {
        $tags = [
            ['name' => 'rupture', 'is_suggested' => true, 'color' => '#EF4444', 'usage_count' => 10],
            ['name' => 'douleur', 'is_suggested' => true, 'color' => '#7C3AED', 'usage_count' => 8],
            ['name' => 'espoir', 'is_suggested' => true, 'color' => '#10B981', 'usage_count' => 12],
            ['name' => 'amour', 'is_suggested' => true, 'color' => '#F59E0B', 'usage_count' => 15],
            ['name' => 'solitude', 'is_suggested' => true, 'color' => '#6B7280', 'usage_count' => 9],
            ['name' => 'reconstruction', 'is_suggested' => true, 'color' => '#3B82F6', 'usage_count' => 7],
            ['name' => 'trahison', 'is_suggested' => true, 'color' => '#DC2626', 'usage_count' => 6],
            ['name' => 'résilience', 'is_suggested' => true, 'color' => '#059669', 'usage_count' => 11],
            ['name' => 'témoignage', 'is_suggested' => true, 'color' => '#EC4899', 'usage_count' => 20],
            ['name' => 'réflexion', 'is_suggested' => true, 'color' => '#8B5CF6', 'usage_count' => 13],
            ['name' => 'poésie', 'is_suggested' => true, 'color' => '#06B6D4', 'usage_count' => 8],
            ['name' => 'guérison', 'is_suggested' => true, 'color' => '#16A34A', 'usage_count' => 9],
            ['name' => 'nostalgie', 'is_suggested' => true, 'color' => '#9333EA', 'usage_count' => 5],
            ['name' => 'pardon', 'is_suggested' => true, 'color' => '#0EA5E9', 'usage_count' => 6],
            ['name' => 'force', 'is_suggested' => true, 'color' => '#DC2626', 'usage_count' => 7],
        ];

        foreach ($tags as $tag) {
            Tag::updateOrCreate(
                ['name' => $tag['name']],
                $tag
            );
        }
    }
}
