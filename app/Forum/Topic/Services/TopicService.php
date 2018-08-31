<?php

namespace App\Forum\Topic\Services;

use App\Forum\Topic\Repositories\TopicRepository;
use Exception;
use Auth;

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
                'message' => 'Topic successfully registered.'
            ];
        } catch (Exception $e) {
            return [
                'type' => 'error',
                'message' => 'Topic error registered.'
            ];
        }
    }

    public function update($data, $id)
    {
        try {
            $this->topicRepository->findOrFail($id);
            $this->topicRepository->update($data);

            return [
                'type' => 'success',
                'message' => 'Topic successfully updated.'
            ];
        } catch (Exception $e) {
            return [
                'type' => 'error',
                'message' => 'Topic error updated.'
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
                'message' => 'Topic deleted successfully.'
            ];
        } catch (Exception $e) {
            return [
                'type' => 'error',
                'message' => 'Topic deleted error.'
            ];
        }
    }
}
