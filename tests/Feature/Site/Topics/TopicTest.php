<?php

namespace Tests\Feature\Site\Topics;

use App\Forum\Topic\Models\Topic;
use App\Forum\Category\Models\Category;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TopicTest extends TestCase
{
    use RefreshDatabase;

    private function topicIndexGetRoute()
    {
        return route('topics.index');
    }

    private function topicSearchGetRoute($params)
    {
        return route('topics.search', $params);
    }

    private function topicShowGetRoute($slug)
    {
        return route('topics.show', $slug);
    }

    /** @test */
    public function it_user_can_view_home_page()
    {
        $response = $this->get($this->topicIndexGetRoute());
        $response->assertSuccessful();
        $response->assertViewHas(['categories', 'topics', 'filters']);

        $response->assertViewIs('site.topics.index');
    }

    /** @test */
    public function it_user_can_view_categories_list()
    {
        $categories = factory(Category::class, 2)->make();

        $response = $this->get($this->topicIndexGetRoute());
        $response->assertSuccessful();

        $categories->each(function($category) use ($response) {
            $response->assertSee($category->title);
            $response->assertSee($category->topics->count());
        });
    }

    /** @test */
    public function it_user_can_view_topics_list()
    {
        $topics = factory(Topic::class, 2)->create([
            'content' => str_random(300)
        ]);

        $response = $this->get($this->topicIndexGetRoute());
        $response->assertSuccessful();

        $topics->each(function($topic) use ($response) {
            $response->assertSee($topic->user->present()->firstLetterName);
            $response->assertSee($topic->title);
            $response->assertSee($topic->category->name);
            $response->assertSee($topic->user->name);
            $response->assertSee($topic->created_at->format('d/m/Y H:i'));
            $response->assertSee($topic->present()->cutContent(200));
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

        $response = $this->get($this->topicSearchGetRoute([
            'category' => $category->slug,
            'keyword' => 'one'
        ]));

        $response->assertStatus(200);
        $response->assertSee($topic->title);
        $response->assertSee($topic->category->name);
        $response->assertSee($topic->user->name);
        $response->assertSee($topic->created_at->format('d/m/Y H:i'));
        $response->assertSee($topic->content);
    }

    /** @test */
    public function filter_can_not_find_topics()
    {
        $category = factory(Category::class)->create();

        $topic = factory(Topic::class)->create([
            'category_id' => $category->id,
            'title' => 'Topic One',
            'content' => 'This is a test text one',
        ]);

        $response = $this->get($this->topicSearchGetRoute([
            'category' => 'Category Not Found',
            'keyword' => 'Topic Not Found'
        ]));

        $response->assertStatus(200);
        $response->assertSee('No topics found :(');
    }

    /** @test */
    public function it_user_can_view_the_topic_page()
    {
        $topic = factory(Topic::class)->create();

        $response = $this->get($this->topicShowGetRoute($topic->slug));
        $response->assertSuccessful();
        $response->assertViewHas(['topic']);
        $response->assertViewIs('site.topics.show');
    }

    /** @test */
    public function it_topic_does_not_exist()
    {
        $topic = factory(Topic::class)->create([
            'title' => 'Teste 1'
        ]);

        $response = $this->get($this->topicShowGetRoute('teste-2'));
        $response->assertStatus(404);
    }
}
