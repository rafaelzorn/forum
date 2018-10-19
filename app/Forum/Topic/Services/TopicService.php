<?php

namespace App\Forum\Topic\Services;

use App\Forum\Topic\Repositories\TopicRepository;
use Exception;
use Auth;
use Lang;

class TopicService
{
    private $topicRepository;

    public function __construct(TopicRepository $topicRepository)
    {
        $this->topicRepository = $topicRepository;
    }

    public function store($data)
    {
        try {
            $data = array_add($data, 'user_id', Auth::user()->id);

            $this->topicRepository->create($data);

            return [
                'type' => 'success',
                'message' => Lang::get('messages.topic_successfully_registered'),
            ];
        } catch (Exception $e) {
            return [
                'type' => 'error',
                'message' => Lang::get('messages.topic_error_registered'),
            ];
        }
    }

    public function update($data, $id)
    {
        try {
            $this->topicRepository->findOrFail($id);
            $this->topicRepository->slug = null;
            $this->topicRepository->update($data);

            return [
                'type' => 'success',
                'message' => Lang::get('messages.topic_successfully_updated'),
            ];
        } catch (Exception $e) {
            return [
                'type' => 'error',
                'message' => Lang::get('messages.topic_error_updated'),
            ];
        }
    }

    public function destroy($id)
    {
        try {
            $this->topicRepository->findOrFail($id);
            $this->topicRepository->delete();

            return [
                'type' => 'success',
                'message' => Lang::get('messages.topic_deleted_successfully'),
            ];
        } catch (Exception $e) {
            return [
                'type' => 'error',
                'message' => Lang::get('messages.topic_deleted_error'),
            ];
        }
    }
}
