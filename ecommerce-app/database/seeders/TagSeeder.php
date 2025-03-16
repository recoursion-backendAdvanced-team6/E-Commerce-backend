<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 例として、ファクトリを利用して20件のタグデータを生成
        // Tag::factory()->count(20)->create();
 
        $seedConfig = config('models.seeding.tag');
        foreach ($seedConfig['default_list'] as $tagData) {
            Tag::updateOrCreate(
                ['name' => $tagData['name']],
                $tagData
            );
        }

    }
}
