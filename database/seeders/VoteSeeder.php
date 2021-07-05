<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Vote;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class VoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i< 100; $i++) {
            Vote::factory()->count(1)
                ->votePosts()
                ->create();
        }

        for ($i=0; $i< 10; $i++) {
            Vote::factory()->count(1)
                ->votePosts('testusersession1234')
                ->create();
        }

        for ($i=0; $i< 100; $i++) {
            Vote::factory()->count(1)
                ->voteComments()
                ->create();
        }

        for ($i=0; $i< 30; $i++) {
            Vote::factory()->count(1)
                ->voteComments('testusersession1234')
                ->create();
        }
    }
}
