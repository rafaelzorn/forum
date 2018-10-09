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
        $query = $this->model->newQuery()
            ->select('topics.*')
            ->joinCategory()
            ->isNotAdmin();

        if (isset($params['category']) && $params['category'] !== '') {
            $query->where('categories.slug', $params['category']);
        }

        if (isset($params['keyword']) && $params['keyword'] !== '') {
            $query->where(function ($query) use ($params) {
                $query->orWhere('topics.title', 'like', '%'.$params['keyword'].'%')
                    ->orWhere('topics.content', 'like', '%'.$params['keyword'].'%');
            });
        }

        if ($active) {
            $query->active();
        }

        if (is_null($take)) {
            return $query->get();
        }

        return $query->paginate($take);
    }
}
