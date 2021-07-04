<?php

namespace Tests\Feature;

use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostActionsTest extends TestCase
{
    protected $faker;

    protected function setUp (): void {
        parent::setUp();
        $this->faker = Factory::create();
    }
}
