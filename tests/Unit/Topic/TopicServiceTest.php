<?php

namespace Tests\Unit\Topic;

use App\Forum\User\Models\User;
use App\Forum\Topic\Models\Topic;
use App\Forum\Topic\Repositories\TopicRepository;
use App\Forum\Topic\Services\TopicService;
use App\Forum\Category\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class TopicServiceTest extends TestCase
{
    use DatabaseMigrations;

    private $topicRepository;
    private $topicService;

    public function setUp()
    {
        parent::setUp();

        $this->topicRepository = new TopicRepository(new Topic);
        $this->topicService = new TopicService($this->topicRepository);
    }

    /** @test */
    public function it_can_store()
    {
        $user = factory(User::class)->create();

        $this->be($user);

        $category = factory(Category::class)->create();

        $data = [
            'category_id' => $category->id,
            'title' => 'Topic One',
            'content' => 'This is a test text',
            'active' => 1
        ];

        $request = $this->topicService->store($data);

        $this->assertCount(1, $topics = Topic::all());

        $topic = $topics->first();

        $this->assertEquals($user->id, $topic->user_id);
        $this->assertEquals($category->id, $topic->category_id);
        $this->assertEquals('Topic One', $topic->title);
        $this->assertEquals('This is a test text', $topic->content);
        $this->assertEquals(1, $topic->active);

        $this->assertEquals('success', $request['type']);
        $this->assertEquals('Topic successfully registered.', $request['message']);
    }

    /** @test */
    public function it_errors_when_store()
    {
        $request = $this->topicService->store([]);

        $this->assertCount(0, $topics = Topic::all());

        $this->assertEquals('error', $request['type']);
        $this->assertEquals('Topic error registered.', $request['message']);
    }

    /** @test */
    public function it_can_update()
    {
        $category = factory(Category::class)->create();

        $topic = factory(Topic::class)->create([
            'category_id' => $category->id,
            'title' => 'Topic One',
            'content' => 'This is a test text',
            'active' => 1
        ]);

        $otherCategory = factory(Category::class)->create([
            'id' => 2
        ]);

        $data = [
            'category_id' => $otherCategory->id,
            'title' => 'Topic Two',
            'content' => 'This is a test text two',
            'active' => 1
        ];

        $request = $this->topicService->update($data, $topic->id);

        $this->assertCount(1, $topics = Topic::all());

        $topic = $topics->first();

        $this->assertEquals($otherCategory->id, $topic->category_id);
        $this->assertEquals('Topic Two', $topic->title);
        $this->assertEquals('This is a test text two', $topic->content);
        $this->assertEquals(1, $topic->active);

        $this->assertEquals('success', $request['type']);
        $this->assertEquals('Topic successfully updated.', $request['message']);
    }

    /** @test */
    public function it_cannot_update_topic_that_does_not_exist()
    {
        $category = factory(Category::class)->create();

        $topic = factory(Topic::class)->create([
            'category_id' => $category->id,
            'title' => 'Topic One',
            'content' => 'This is a test text',
            'active' => 1
        ]);

        $otherCategory = factory(Category::class)->create([
            'id' => 2
        ]);

        $data = [
            'category_id' => $otherCategory->id,
            'title' => 'Topic Two',
            'content' => 'This is a test text two',
            'active' => 1
        ];

        $request = $this->topicService->update($data, 999);

        $this->assertCount(1, $topics = Topic::all());

        $topic = $topics->first();

        $this->assertEquals($category->id, $topic->category_id);
        $this->assertEquals('Topic One', $topic->title);
        $this->assertEquals('This is a test text', $topic->content);
        $this->assertEquals(1, $topic->active);

        $this->assertEquals('error', $request['type']);
        $this->assertEquals('Topic error updated.', $request['message']);
    }

    /** @test */
    public function it_can_destroy()
    {
        $topic = factory(Topic::class)->create();

        $request = $this->topicService->destroy($topic->id);

        $this->assertCount(0, $topics = Topic::all());

        $this->assertEquals('success', $request['type']);
        $this->assertEquals('Topic deleted successfully.', $request['message']);
    }

    /** @test */
    public function it_cannot_destroy_category_that_does_not_exist()
    {
        $topic = factory(Topic::class)->create();

        $request = $this->topicService->destroy(999);

        $this->assertCount(1, $topics = Category::all());

        $this->assertEquals('error', $request['type']);
        $this->assertEquals('Topic deleted error.', $request['message']);
    }
}
