<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GenreSeeder extends Seeder
{
    public function run(): void
    {
        $genres = [
            ['name' => 'Historical'],
            ['name' => 'Romance'],
            ['name' => 'Fantasy'],
            ['name' => 'Action'],
            ['name' => 'Adventure'],
            ['name' => 'Comedy'],
            ['name' => 'Mystery'],
            ['name' => 'Thriller'],
            ['name' => 'Martial Arts'],
            ['name' => 'Drama'],
            ['name' => 'Family'],
            ['name' => 'Political'],
            ['name' => 'Sci-Fi'],
            ['name' => 'Crime'],
            ['name' => 'Suspense'],
            ['name' => 'Slice of Life'],
            ['name' => 'Tragedy'],
            ['name' => 'Mythical'],
            ['name' => 'Modern'],
            ['name' => 'Supernatural'],
        ];

        foreach ($genres as $genre) {
            DB::table('genres')->insert([
                'id' => Str::uuid(),
                'name' => $genre['name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
