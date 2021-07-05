<?php

namespace Database\Seeders;

use App\Models\Comment;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i< 100; $i++) {
            Comment::factory()->count(1)
                ->create();
        }

        for ($i=0; $i< 15; $i++) {
            Comment::factory()->count(1)
                ->differentPost()
                ->create();
        }

        for ($i=0; $i< 50; $i++) {
            Comment::factory()->count(1)
                ->toComment()
                ->create();
        }

        for ($i=0; $i< 5; $i++) {
            Comment::factory()->count(1)
                ->differentPost('testusersession1234')
                ->create();
        }

        for ($i=0; $i< 7; $i++) {
            Comment::factory()->count(1)
                ->toComment('testusersession1234')
                ->create();
        }
    }
}
