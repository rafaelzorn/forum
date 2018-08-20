<?php

namespace Tests\Unit\Topic;

use App\Forum\Topic\Models\Topic;
use App\Forum\Topic\Repositories\TopicRepository;
use App\Forum\Topic\Services\TopicService;
use Tests\TestCase;

class TopicUnitTest extends TestCase
{
    protected $topicRepository;
    protected $topicService;

    public function setUp()
    {
        parent::setUp();

        $this->topicRepository = new TopicRepository(new Topic);
        $this->topicService = new TopicService($this->topicRepository);
    }

    public function test_service_store_topics_successful()
    {
        $data = [
            'category_id' => $this->category->id,
            'title' => 'Título de teste',
            'content' => 'Conteúdo de teste.',
            'active' => 1
        ];

        $request = $this->topicService->store($data);

        $this->assertEquals('success', $request['type']);
        $this->assertEquals('Topic successfully registered.', $request['message']);
    }

    public function test_service_store_topics_error()
    {
        $request = $this->topicService->store([]);

        $this->assertEquals('error', $request['type']);
        $this->assertEquals('Topic error registered.', $request['message']);
    }

    public function test_service_update_topics_successful()
    {
        $topic = factory(Topic::class)->create();

        $data = [
            'category_id' => $this->category->id,
            'title' => 'Título de teste',
            'content' => 'Conteúdo de teste.',
            'active' => 1
        ];

        $request = $this->topicService->update($data, $topic->id);

        $this->assertEquals('success', $request['type']);
        $this->assertEquals('Topic successfully updated.', $request['message']);
    }

    public function test_service_update_topics_error()
    {
        $request = $this->topicService->update([], 999);

        $this->assertEquals('error', $request['type']);
        $this->assertEquals('Topic error updated.', $request['message']);
    }

    public function test_service_destroy_topics_successful()
    {
        $topic = factory(Topic::class)->create();

        $request = $this->topicService->destroy($topic->id);

        $this->assertEquals('success', $request['type']);
        $this->assertEquals('Topic deleted successfully.', $request['message']);
    }

    public function test_service_destroy_topics_error()
    {
        $request = $this->topicService->destroy(999);

        $this->assertEquals('error', $request['type']);
        $this->assertEquals('Topic deleted error.', $request['message']);
    }
}
