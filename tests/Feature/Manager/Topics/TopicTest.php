<?php

namespace Tests\Feature\Manager\Topic;

use App\Forum\Topic\Models\Topic;
use App\Forum\Category\Models\Category;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TopicTest extends TestCase
{
    use RefreshDatabase;

    private function topicIndexGetRoute()
    {
        return route('manager.topics.index');
    }

    private function topicCreateGetRoute()
    {
        return route('manager.topics.create');
    }

    private function topicStoreRoute()
    {
        return route('manager.topics.store');
    }

    private function topicEditGetRoute($id)
    {
        return route('manager.topics.edit', $id);
    }

    private function topicUpdateRoute($id)
    {
        return route('manager.topics.update', $id);
    }

    private function topicDeleteRoute($id)
    {
        return route('manager.topics.destroy', $id);
    }

    /** @test */
    public function it_admin_can_view_topic_pages()
    {
        $topic = factory(Topic::class)->create();

        $pages = [
            ['route' => route('manager.topics.index'), 'view' => 'manager.topics.index'],
            ['route' => route('manager.topics.create'), 'view' => 'manager.topics.form'],
            ['route' => route('manager.topics.edit', $topic->id), 'view' => 'manager.topics.form'],
        ];

        foreach ($pages as $page) {
            $response = $this->actingAs($this->user)->get($page['route']);
            $response->assertSuccessful();
            $response->assertViewIs($page['view']);
        }
    }

    /** @test */
    public function it_user_can_view_topic_pages()
    {
        $topic = factory(Topic::class)->create([
            'user_id' => $this->user->id
        ]);

        $pages = [
            ['route' => route('manager.topics.index'), 'view' => 'manager.topics.index'],
            ['route' => route('manager.topics.create'), 'view' => 'manager.topics.form'],
            ['route' => route('manager.topics.edit', $topic->id), 'view' => 'manager.topics.form'],
        ];

        foreach ($pages as $page) {
            $response = $this->actingAs($this->user)->get($page['route']);
            $response->assertSuccessful();
            $response->assertViewIs($page['view']);
        }
    }

    /** @test */
    public function it_user_can_view_a_create_form()
    {
        $response = $this->actingAs($this->user)->get($this->topicCreateGetRoute());

        $response->assertSuccessful();
        $response->assertViewHas(['currentPage', 'edit', 'topic', 'categories']);
        $response->assertViewIs('manager.topics.form');
    }

    /** @test */
    public function it_user_can_create_a_topic()
    {
        $category = factory(Category::class)->create();

        $response = $this->actingAs($this->user)->post($this->topicStoreRoute(), [
            'category_id' => $category->id,
            'title' => 'Topic One',
            'content' => 'This is a test text',
            'active' => 1,
        ]);

        $this->assertCount(1, $topics = Topic::all());

        $topic = $topics->first();

        $this->assertEquals($this->user->id, $topic->user_id);
        $this->assertEquals($category->id, $topic->category_id);
        $this->assertEquals('Topic One', $topic->title);
        $this->assertEquals('This is a test text', $topic->content);
        $this->assertEquals(1, $topic->active);

        $response->assertRedirect($this->topicIndexGetRoute());
        $response->assertSessionHas('message', [
            'type' => 'success',
            'message' => 'Topic successfully registered.',
        ]);
    }

    /** @test */
    public function it_user_cannot_create_a_topic_without_category()
    {
        $response = $this->actingAs($this->user)->from($this->topicCreateGetRoute())->post($this->topicStoreRoute(), [
            'category_id' => '',
            'title' => 'Topic One',
            'content' => 'This is a test text',
            'active' => 1,
        ]);

        $this->assertCount(0, Topic::all());
        $response->assertRedirect($this->topicCreateGetRoute());
        $response->assertSessionHasErrors(['category_id' => 'The category field is required.']);
        $this->assertTrue(session()->hasOldInput('title'));
        $this->assertTrue(session()->hasOldInput('content'));
        $this->assertTrue(session()->hasOldInput('active'));
    }

    /** @test */
    public function it_user_cannot_create_a_topic_without_title()
    {
        $category = factory(Category::class)->create();

        $response = $this->actingAs($this->user)->from($this->topicCreateGetRoute())->post($this->topicStoreRoute(), [
            'category_id' => $category->id,
            'title' => '',
            'content' => 'This is a test text',
            'active' => 1,
        ]);

        $this->assertCount(0, Topic::all());
        $response->assertRedirect($this->topicCreateGetRoute());
        $response->assertSessionHasErrors(['title' => 'The title field is required.']);
        $this->assertTrue(session()->hasOldInput('category_id'));
        $this->assertTrue(session()->hasOldInput('content'));
        $this->assertTrue(session()->hasOldInput('active'));
    }

    /** @test */
    public function it_user_cannot_create_a_topic_without_content()
    {
        $category = factory(Category::class)->create();

        $response = $this->actingAs($this->user)->from($this->topicCreateGetRoute())->post($this->topicStoreRoute(), [
            'category_id' => $category->id,
            'title' => 'Topic One',
            'content' => '',
            'active' => 1,
        ]);

        $this->assertCount(0, Topic::all());
        $response->assertRedirect($this->topicCreateGetRoute());
        $response->assertSessionHasErrors(['content' => 'The content field is required.']);
        $this->assertTrue(session()->hasOldInput('category_id'));
        $this->assertTrue(session()->hasOldInput('title'));
        $this->assertTrue(session()->hasOldInput('active'));
    }

    /** @test */
    public function it_user_cannot_create_a_topic_without_situation()
    {
        $category = factory(Category::class)->create();

        $response = $this->actingAs($this->user)->from($this->topicCreateGetRoute())->post($this->topicStoreRoute(), [
            'category_id' => $category->id,
            'title' => 'Topic One',
            'content' => 'This is a test text',
            'active' => '',
        ]);

        $this->assertCount(0, Topic::all());
        $response->assertRedirect($this->topicCreateGetRoute());
        $response->assertSessionHasErrors(['active' => 'The situation field is required.']);
        $this->assertTrue(session()->hasOldInput('category_id'));
        $this->assertTrue(session()->hasOldInput('title'));
        $this->assertTrue(session()->hasOldInput('content'));
    }

    /** @test */
    public function it_user_can_view_a_edit_form()
    {
        $topic = factory(Topic::class)->create();

        $response = $this->actingAs($this->user)->get($this->topicEditGetRoute($topic->id));
        $response->assertSuccessful();

        $response->assertViewHas(['currentPage', 'edit', 'topic', 'categories']);
        $response->assertViewIs('manager.topics.form');
    }

    /** @test */
    public function it_user_can_edit_the_topic()
    {
        $category = factory(Category::class)->create();

        $topic = factory(Topic::class)->create([
            'user_id' => $this->user->id,
            'category_id' => $category->id,
            'title' => 'Topic One',
            'content' => 'This is a test text one',
            'active' => 1,
        ]);

        $otherCategory = factory(Category::class)->create();

        $response = $this->actingAs($this->user)->put($this->topicUpdateRoute($topic->id), [
            'category_id' => $otherCategory->id,
            'title' => 'Topic Two',
            'content' => 'This is a test text two',
            'active' => 1,
        ]);

        $this->assertCount(1, $topics = Topic::all());

        $topic = $topics->first();

        $this->assertEquals($this->user->id, $topic->user_id);
        $this->assertEquals($otherCategory->id, $topic->category_id);
        $this->assertEquals('Topic Two', $topic->title);
        $this->assertEquals('This is a test text two', $topic->content);
        $this->assertEquals(1, $topic->active);

        $response->assertRedirect($this->topicIndexGetRoute());
        $response->assertSessionHas('message', [
            'type' => 'success',
            'message' => 'Topic successfully updated.',
        ]);
    }

    /** @test */
    public function it_user_cannot_update_the_topic_without_category()
    {
        $category = factory(Category::class)->create();

        $topic = factory(Topic::class)->create([
            'user_id' => $this->user->id,
            'category_id' => $category->id,
            'title' => 'Topic One',
            'content' => 'This is a test text one',
            'active' => 1,
        ]);

        $response = $this->actingAs($this->user)->from($this->topicEditGetRoute($topic->id))->put($this->topicUpdateRoute($topic->id), [
            'category_id' => '',
            'title' => 'Topic Two',
            'content' => 'This is a test text two',
            'active' => 1,
        ]);

        $this->assertCount(1, $topics = Topic::all());

        $topic = $topics->first();

        $this->assertEquals($this->user->id, $topic->user_id);
        $this->assertEquals($category->id, $topic->category_id);
        $this->assertEquals('Topic One', $topic->title);
        $this->assertEquals('This is a test text one', $topic->content);
        $this->assertEquals(1, $topic->active);

        $response->assertRedirect($this->topicEditGetRoute($topic->id));
        $response->assertSessionHasErrors(['category_id' => 'The category field is required.']);
        $this->assertTrue(session()->hasOldInput('title'));
        $this->assertTrue(session()->hasOldInput('content'));
        $this->assertTrue(session()->hasOldInput('active'));
    }

    /** @test */
    public function it_user_cannot_update_the_topic_without_title()
    {
        $category = factory(Category::class)->create();

        $topic = factory(Topic::class)->create([
            'user_id' => $this->user->id,
            'category_id' => $category->id,
            'title' => 'Topic One',
            'content' => 'This is a test text one',
            'active' => 1,
        ]);

        $otherCategory = factory(Category::class)->create();

        $response = $this->actingAs($this->user)->from($this->topicEditGetRoute($topic->id))->put($this->topicUpdateRoute($topic->id), [
            'category_id' => $otherCategory->id,
            'title' => '',
            'content' => 'This is a test text two',
            'active' => 1,
        ]);

        $this->assertCount(1, $topics = Topic::all());

        $topic = $topics->first();

        $this->assertEquals($this->user->id, $topic->user_id);
        $this->assertEquals($category->id, $topic->category_id);
        $this->assertEquals('Topic One', $topic->title);
        $this->assertEquals('This is a test text one', $topic->content);
        $this->assertEquals(1, $topic->active);

        $response->assertRedirect($this->topicEditGetRoute($topic->id));
        $response->assertSessionHasErrors(['title' => 'The title field is required.']);
        $this->assertTrue(session()->hasOldInput('category_id'));
        $this->assertTrue(session()->hasOldInput('content'));
        $this->assertTrue(session()->hasOldInput('active'));
    }

    /** @test */
    public function it_user_cannot_update_the_topic_without_content()
    {
        $category = factory(Category::class)->create();

        $topic = factory(Topic::class)->create([
            'user_id' => $this->user->id,
            'category_id' => $category->id,
            'title' => 'Topic One',
            'content' => 'This is a test text one',
            'active' => 1,
        ]);

        $otherCategory = factory(Category::class)->create();

        $response = $this->actingAs($this->user)->from($this->topicEditGetRoute($topic->id))->put($this->topicUpdateRoute($topic->id), [
            'category_id' => $otherCategory->id,
            'title' => 'Topic Two',
            'content' => '',
            'active' => 1,
        ]);

        $this->assertCount(1, $topics = Topic::all());

        $topic = $topics->first();

        $this->assertEquals($this->user->id, $topic->user_id);
        $this->assertEquals($category->id, $topic->category_id);
        $this->assertEquals('Topic One', $topic->title);
        $this->assertEquals('This is a test text one', $topic->content);
        $this->assertEquals(1, $topic->active);

        $response->assertRedirect($this->topicEditGetRoute($topic->id));
        $response->assertSessionHasErrors(['content' => 'The content field is required.']);
        $this->assertTrue(session()->hasOldInput('category_id'));
        $this->assertTrue(session()->hasOldInput('title'));
        $this->assertTrue(session()->hasOldInput('active'));
    }

    /** @test */
    public function it_user_cannot_update_the_topic_without_situation()
    {
        $category = factory(Category::class)->create();

        $topic = factory(Topic::class)->create([
            'user_id' => $this->user->id,
            'category_id' => $category->id,
            'title' => 'Topic One',
            'content' => 'This is a test text one',
            'active' => 1,
        ]);

        $otherCategory = factory(Category::class)->create();

        $response = $this->actingAs($this->user)->from($this->topicEditGetRoute($topic->id))->put($this->topicUpdateRoute($topic->id), [
            'category_id' => $otherCategory->id,
            'title' => 'Topic Two',
            'content' => 'This is a test text two',
            'active' => '',
        ]);

        $this->assertCount(1, $topics = Topic::all());

        $topic = $topics->first();

        $this->assertEquals($this->user->id, $topic->user_id);
        $this->assertEquals($category->id, $topic->category_id);
        $this->assertEquals('Topic One', $topic->title);
        $this->assertEquals('This is a test text one', $topic->content);
        $this->assertEquals(1, $topic->active);

        $response->assertRedirect($this->topicEditGetRoute($topic->id));
        $response->assertSessionHasErrors(['active' => 'The situation field is required.']);
        $this->assertTrue(session()->hasOldInput('category_id'));
        $this->assertTrue(session()->hasOldInput('title'));
        $this->assertTrue(session()->hasOldInput('content'));
    }

    /** @test */
    public function it_user_can_delete_a_category()
    {
        $topic = factory(Topic::class)->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user)->from($this->topicIndexGetRoute())->delete($this->topicDeleteRoute($topic->id));

        $this->assertCount(0, Topic::all());
        $response->assertRedirect($this->topicIndexGetRoute());
        $response->assertSessionHas('message', [
            'type' => 'success',
            'message' => 'Topic deleted successfully.',
        ]);
    }
}
