<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AnonymousActionsTest extends TestCase
{
    public function test_site_contains_anon_data () {
        $response = $this->json('GET', '/api/');
        $response->assertJsonStructure([
            'username'
        ]);
    }
}
