<?php

namespace Tests\Unit\Topic;

use App\Forum\Topic\Models\Topic;
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
}
