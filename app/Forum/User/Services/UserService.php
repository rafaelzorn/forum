<?php

namespace App\Forum\User\Services;

use App\Forum\User\Repositories\UserRepository;

class UserService
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
}
