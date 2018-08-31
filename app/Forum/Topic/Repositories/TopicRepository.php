<?php

namespace App\Forum\Topic\Repositories;

use App\Forum\Base\Repositories\BaseRepository;
use App\Forum\Topic\Repositories\Contracts\TopicRepositoryInterface;
use App\Forum\Topic\Models\Topic;
use Auth;

class TopicRepository extends BaseRepository implements TopicRepositoryInterface
{
    public function __construct(Topic $topic)
    {
        $this->model = $topic;
    }

    public function filter($params = [], $take = null)
    {
        $user = Auth::user();

        $query = $this->model->newQuery();

        if (!$user->isAdmin()) {
            $query->where('user_id', '=', $user->id);
        }

        if (is_null($take)) {
            return $query->get();
        }

        return $query->paginate($take);
    }
}
