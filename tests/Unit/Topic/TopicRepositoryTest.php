<?php

namespace Tests\Unit\Topic;

use App\Forum\User\Models\User;
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

    public function test_admin_can_view_all_topics()
    {
        $user = factory(User::class, 'admin')->create();

        factory(Topic::class, 2)->create([
            'user_id' => $user->id,
        ]);

        factory(Topic::class, 3)->create([
            'user_id' => 4,
        ]);

        $this->be($user);

        $topics = $this->topicRepository->filter();

        $this->assertCount(5, $topics);
    }

    public function test_user_can_view_your_topics()
    {
        $user = factory(User::class)->create();

        factory(Topic::class, 2)->create([
            'user_id' => $user->id,
        ]);

        factory(Topic::class, 3)->create([
            'user_id' => 4,
        ]);

        $this->be($user);

        $topics = $this->topicRepository->filter();

        $this->assertCount(2, $topics);
    }
}
