<?php

namespace Tests;

use App\Forum\User\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Faker\Factory as Faker;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseMigrations, DatabaseTransactions;

    protected $faker;
    protected $user;
    protected $admin;

    public function setUp()
    {
        parent::setUp();

        $this->faker = Faker::create();
        $this->user  = factory(User::class)->create();
        $this->admin = factory(User::class, 'admin')->create();
    }

    public function tearDown()
    {
        $this->artisan('migrate:reset');
        parent::tearDown();
    }
}
