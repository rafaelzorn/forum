<?php

namespace Tests\Unit\User;

use App\Forum\User\Models\User;
use App\Forum\User\Repositories\UserRepository;
use App\Forum\User\Services\UserService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Hash;

class UserServiceTest extends TestCase
{
    use DatabaseMigrations;

    private $userRepository;
    private $userService;

    public function setUp()
    {
        parent::setUp();

        $this->userRepository = new UserRepository(new User);
        $this->userService = new UserService($this->userRepository);
    }

    /** @test */
    public function it_can_store()
    {
        $request = $this->userService->store([
            'name'     => 'User One',
            'email'    => 'user@user.com.br',
            'password' => '123456',
            'is_admin' => true,
            'active'   => true
        ]);

        $this->assertCount(1, $users = User::all());

        $user = $users->first();

        $this->assertEquals('User One', $user->name);
        $this->assertEquals('user@user.com.br', $user->email);
        $this->assertTrue(Hash::check('123456', $user->password));
        $this->assertEquals(true, $user->is_admin);
        $this->assertEquals(true, $user->active);

        $this->assertEquals('success', $request['type']);
        $this->assertEquals('User successfully registered.', $request['message']);
    }

    /** @test */
    public function it_errors_when_store()
    {
        $request = $this->userService->store([]);

        $this->assertCount(0, $users = User::all());

        $this->assertEquals('error', $request['type']);
        $this->assertEquals('User error registered.', $request['message']);
    }

    /** @test */
    public function it_can_update()
    {
        $user = factory(User::class)->create([
            'name'     => 'User One',
            'email'    => 'user@user.com.br',
            'password' => Hash::make('123456'),
            'is_admin' => true,
            'active'   => true
        ]);

        $request = $this->userService->update(
            [
                'name'     => 'User Two',
                'email'    => 'user-2@user-2.com.br',
                'password' => '1234567',
                'is_admin' => true,
                'active'   => true
            ],
            $user->id
        );

        $this->assertCount(1, $users = User::all());

        $user = $users->first();

        $this->assertEquals('User Two', $user->name);
        $this->assertEquals('user-2@user-2.com.br', $user->email);
        $this->assertTrue(Hash::check('1234567', $user->password));
        $this->assertEquals(true, $user->is_admin);
        $this->assertEquals(true, $user->active);

        $this->assertEquals('success', $request['type']);
        $this->assertEquals('User successfully updated.', $request['message']);
    }

    /** @test */
    public function it_cannot_update_user_that_does_not_exist()
    {
        $user = factory(User::class)->create([
            'name'     => 'User One',
            'email'    => 'user@user.com.br',
            'password' => Hash::make('123456'),
            'is_admin' => true,
            'active'   => true
        ]);

        $request = $this->userService->update(
            [
                'name'     => 'User Two',
                'email'    => 'user-2@user-2.com.br',
                'password' => '1234567',
                'is_admin' => true,
                'active'   => true
            ],
            999
        );

        $this->assertCount(1, $users = User::all());

        $user = $users->first();

        $this->assertEquals('User One', $user->name);
        $this->assertEquals('user@user.com.br', $user->email);
        $this->assertTrue(Hash::check('123456', $user->password));
        $this->assertEquals(true, $user->is_admin);
        $this->assertEquals(true, $user->active);

        $this->assertEquals('error', $request['type']);
        $this->assertEquals('User error updated.', $request['message']);
    }

    /** @test */
    public function it_can_destroy()
    {
        $user = factory(User::class)->create();

        $request = $this->userService->destroy($user->id);

        $this->assertCount(0, $users = User::all());

        $this->assertEquals('success', $request['type']);
        $this->assertEquals('User deleted successfully.', $request['message']);
    }

    /** @test */
    public function it_cannot_destroy_user_that_does_not_exist()
    {
        $user = factory(User::class)->create();

        $request = $this->userService->destroy(999);

        $this->assertCount(1, $users = User::all());

        $this->assertEquals('error', $request['type']);
        $this->assertEquals('User deleted error.', $request['message']);
    }
}
