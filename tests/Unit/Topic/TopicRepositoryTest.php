<?php

namespace Tests\Unit\Topic;

use App\Forum\Topic\Models\Topic;
use App\Forum\Category\Models\Category;
use App\Forum\Topic\Repositories\TopicRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class TopicRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    private $topicRepository;

    public function setUp()
    {
        parent::setUp();

        $this->topicRepository = new TopicRepository(new Topic);
    }

    /** @test */
    public function it_admin_can_view_all_topics()
    {
        factory(Topic::class, 2)->create([
            'user_id' => $this->admin->id,
        ]);

        factory(Topic::class, 3)->create([
            'user_id' => 4,
        ]);

        $this->be($this->admin);

        $topics = $this->topicRepository->filter();

        $this->assertCount(5, $topics);
    }

    /** @test */
    public function it_user_can_view_only_your_topics()
    {
        factory(Topic::class, 2)->create([
            'user_id' => $this->user->id,
        ]);

        factory(Topic::class, 3)->create([
            'user_id' => 4,
        ]);

        $this->be($this->user);

        $topics = $this->topicRepository->filter();

        $this->assertCount(2, $topics);
    }

    /** @test */
    public function it_user_can_view_only_active_topics()
    {
        factory(Topic::class)->create([
            'active' => true
        ]);

        factory(Topic::class)->create([
            'active' => false
        ]);

        $topics = $this->topicRepository->filter([], null, true);

        $this->assertCount(1, $topics);
    }

    /** @test */
    public function it_user_can_filter_topics_by_category_slug()
    {
        $category = factory(Category::class)->create();

        $topic = factory(Topic::class)->create([
            'category_id' => $category->id
        ]);

        factory(Topic::class)->create();

        $params = [
            'category' => $category->slug
        ];

        $topics = $this->topicRepository->filter($params, null, true);

        $this->assertCount(1, $topics);

        $topics->each(function ($item) use ($topic) {
            $this->assertEquals($item->user_id, $topic->user_id);
            $this->assertEquals($item->category_id, $topic->category_id);
            $this->assertEquals($item->title, $topic->title);
            $this->assertEquals($item->slug, $topic->slug);
            $this->assertEquals($item->content, $topic->content);
            $this->assertEquals($item->active, $topic->active);
        });
    }

    /** @test */
    public function it_user_can_filter_topics_by_title_or_content()
    {
        $category = factory(Category::class)->create();

        $topic = factory(Topic::class)->create([
            'category_id' => $category->id,
            'title' => 'Topic One',
            'content' => 'This is a test text one',
        ]);

        factory(Topic::class)->create();

        $params = [
            'keyword' => 'one'
        ];

        $topics = $this->topicRepository->filter($params, null, true);

        $this->assertCount(1, $topics);

        $topics->each(function ($item) use ($topic) {
            $this->assertEquals($item->user_id, $topic->user_id);
            $this->assertEquals($item->category_id, $topic->category_id);
            $this->assertEquals($item->title, $topic->title);
            $this->assertEquals($item->slug, $topic->slug);
            $this->assertEquals($item->content, $topic->content);
            $this->assertEquals($item->active, $topic->active);
        });
    }

    /** @test */
    public function it_user_can_filter_topics_by_title_or_content_and_category_slug()
    {
        $category = factory(Category::class)->create();

        $topic = factory(Topic::class)->create([
            'category_id' => $category->id,
            'title' => 'Topic One',
            'content' => 'This is a test text one',
        ]);

        factory(Topic::class)->create();

        $params = [
            'category' => $category->slug,
            'keyword' => 'one'
        ];

        $topics = $this->topicRepository->filter($params, null, true);

        $this->assertCount(1, $topics);

        $topics->each(function ($item) use ($topic) {
            $this->assertEquals($item->user_id, $topic->user_id);
            $this->assertEquals($item->category_id, $topic->category_id);
            $this->assertEquals($item->title, $topic->title);
            $this->assertEquals($item->slug, $topic->slug);
            $this->assertEquals($item->content, $topic->content);
            $this->assertEquals($item->active, $topic->active);
        });
    }
}
