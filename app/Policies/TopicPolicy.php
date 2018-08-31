<?php

namespace App\Policies;

use App\Forum\User\Models\User;
use App\Forum\Topic\Models\Topic;
use Illuminate\Auth\Access\HandlesAuthorization;

class TopicPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function edit(User $user, Topic $topic)
    {
        return $user->id == $topic->user_id;
    }
}
