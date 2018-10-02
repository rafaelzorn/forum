<?php

namespace App\Forum\Topic\Repositories;

use App\Forum\Base\Repositories\BaseRepository;
use App\Forum\Topic\Repositories\Contracts\TopicRepositoryInterface;
use App\Forum\Topic\Models\Topic;

class TopicRepository extends BaseRepository implements TopicRepositoryInterface
{
    public function __construct(Topic $topic)
    {
        $this->model = $topic;
    }

    public function filter($params = [], $take = null, $active = false)
    {
        $query = $this->model->newQuery()->isAdmin();

        if ($active) {
            $query->active();
        }

        if (is_null($take)) {
            return $query->get();
        }

        return $query->paginate($take);
    }
}
