<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Post::factory()->count(20)->create();
        Post::factory()->count(20)->withImg()->create();
        Post::factory()->count(5)->withVideo()->create();
        Post::factory(5)->withPredefinedUsername()->create();
    }
}
