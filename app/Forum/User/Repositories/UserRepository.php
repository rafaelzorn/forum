<?php

namespace App\Forum\User\Repositories;

use App\Forum\Base\Repositories\BaseRepository;
use App\Forum\User\Repositories\Contracts\UserRepositoryInterface;
use App\Forum\User\Models\User;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $user)
    {
        $this->model = $user;
    }
}
