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
}
