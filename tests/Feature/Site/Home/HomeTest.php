<?php

namespace Tests\Feature\Site\Home;

use App\Forum\Topic\Models\Topic;
use App\Forum\Category\Models\Category;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    private function homeIndexGetRoute()
    {
        return route('home');
    }

    private function filterGetRoute($params)
    {
        return route('search.topics', $params);
    }

    /** @test */
    public function it_user_can_view_home_page()
    {
        $response = $this->get($this->homeIndexGetRoute());
        $response->assertSuccessful();
        $response->assertViewHas(['categories', 'topics', 'filters']);

        $response->assertViewIs('site.home.index');
    }

    /** @test */
    public function it_user_can_view_categories_list()
    {
        $categories = factory(Category::class, 2)->make();

        $response = $this->get($this->homeIndexGetRoute());
        $response->assertSuccessful();

        $categories->each(function($category) use ($response) {
            $response->assertSee($category->title);
        });
    }

    /** @test */
    public function it_user_can_view_topics_list()
    {
        $topics = factory(Topic::class, 2)->create();

        $response = $this->get($this->homeIndexGetRoute());
        $response->assertSuccessful();

        $topics->each(function($topic) use ($response) {
            $response->assertSee($topic->title);
            $response->assertSee($topic->category->name);
            $response->assertSee($topic->user->name);
            $response->assertSee($topic->created_at->format('d/m/Y H:i'));
            $response->assertSee($topic->content);
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

        $response = $this->get($this->filterGetRoute([
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

        $response = $this->get($this->filterGetRoute([
            'category' => 'Category Not Found',
            'keyword' => 'Topic Not Found'
        ]));

        $response->assertStatus(200);
        $response->assertSee('No topics found :(');
    }
}
