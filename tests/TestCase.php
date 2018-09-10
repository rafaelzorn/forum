<?php

namespace Tests;

use App\Forum\User\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $user;
    protected $admin;

    public function setUp()
    {
        parent::setUp();

        # User common
        $this->user = factory(User::class)->make(['id' => 1]);

        # Adminstrator
        $this->admin = factory(User::class, 'admin')->make(['id' => 1]);
    }
}
