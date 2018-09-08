<?php

namespace Tests\Feature\Manager\Topic;

use App\Forum\User\Models\User;
use App\Forum\Topic\Models\Topic;
use App\Forum\Category\Models\Category;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TopicTest extends TestCase
{
    use RefreshDatabase;

    protected function topicIndexGetRoute()
    {
        return route('manager.topics.index');
    }

    protected function topicCreateGetRoute()
    {
        return route('manager.topics.create');
    }

    protected function topicStoreRoute()
    {
        return route('manager.topics.store');
    }

    protected function topicEditGetRoute($id)
    {
        return route('manager.topics.edit', $id);
    }

    protected function topicUpdateRoute($id)
    {
        return route('manager.topics.update', $id);
    }

    protected function topicDeleteRoute($id)
    {
        return route('manager.topics.destroy', $id);
    }

    public function test_admin_can_view_topic_pages()
    {
        $user = factory(User::class, 'admin')->make();
        $topic = factory(Topic::class)->create();

        $pages = [
            ['route' => route('manager.topics.index'), 'view' => 'manager.topics.index'],
            ['route' => route('manager.topics.create'), 'view' => 'manager.topics.form'],
            ['route' => route('manager.topics.edit', $topic->id), 'view' => 'manager.topics.form'],
        ];

        foreach ($pages as $page) {
            $response = $this->actingAs($user)->get($page['route']);
            $response->assertSuccessful();
            $response->assertViewIs($page['view']);
        }
    }

    public function test_user_can_view_topic_pages()
    {
        $user = factory(User::class)->make([
            'id' => 1,
        ]);

        $topic = factory(Topic::class)->create([
            'user_id' => $user->id
        ]);

        $pages = [
            ['route' => route('manager.topics.index'), 'view' => 'manager.topics.index'],
            ['route' => route('manager.topics.create'), 'view' => 'manager.topics.form'],
            ['route' => route('manager.topics.edit', $topic->id), 'view' => 'manager.topics.form'],
        ];

        foreach ($pages as $page) {
            $response = $this->actingAs($user)->get($page['route']);
            $response->assertSuccessful();
            $response->assertViewIs($page['view']);
        }
    }

    public function test_user_can_view_a_create_form()
    {
        $user = factory(User::class)->make();

        $response = $this->actingAs($user)->get($this->topicCreateGetRoute());

        $response->assertSuccessful();
        $response->assertViewHas(['currentPage', 'edit', 'topic', 'categories']);
        $response->assertViewIs('manager.topics.form');
    }

    public function test_user_can_create_a_topic()
    {
        $user = factory(User::class)->create();
        $category = factory(Category::class)->create();

        $response = $this->actingAs($user)->post($this->topicStoreRoute(), [
            'category_id' => $category->id,
            'title' => 'Topic One',
            'content' => 'This is a test text',
            'active' => 1,
        ]);

        $this->assertCount(1, $topics = Topic::all());

        $topic = $topics->first();

        $this->assertEquals($user->id, $topic->user_id);
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

    public function test_user_cannot_create_topic_without_category()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->from($this->topicCreateGetRoute())->post($this->topicStoreRoute(), [
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

    public function test_user_cannot_create_topic_without_title()
    {
        $user = factory(User::class)->create();
        $category = factory(Category::class)->create();

        $response = $this->actingAs($user)->from($this->topicCreateGetRoute())->post($this->topicStoreRoute(), [
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

    public function test_user_cannot_create_topic_without_content()
    {
        $user = factory(User::class)->create();
        $category = factory(Category::class)->create();

        $response = $this->actingAs($user)->from($this->topicCreateGetRoute())->post($this->topicStoreRoute(), [
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

    public function test_user_cannot_create_topic_without_situation()
    {
        $user = factory(User::class)->create();
        $category = factory(Category::class)->create();

        $response = $this->actingAs($user)->from($this->topicCreateGetRoute())->post($this->topicStoreRoute(), [
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

    public function test_user_can_view_a_edit_form()
    {
        $user = factory(User::class, 'admin')->make();
        $topic = factory(Topic::class)->create();

        $response = $this->actingAs($user)->get($this->topicEditGetRoute($topic->id));
        $response->assertSuccessful();

        $response->assertViewHas(['currentPage', 'edit', 'topic', 'categories']);
        $response->assertViewIs('manager.topics.form');
    }

    public function test_user_can_edit_a_topic()
    {
        $user = factory(User::class)->create();
        $category = factory(Category::class)->create();
        $topic = factory(Topic::class)->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Topic One',
            'content' => 'This is a test text one',
            'active' => 1,
        ]);

        $otherCategory = factory(Category::class)->create([
            'id' => 2
        ]);

        $response = $this->actingAs($user)->put($this->topicUpdateRoute($topic->id), [
            'category_id' => $otherCategory->id,
            'title' => 'Topic Two',
            'content' => 'This is a test text two',
            'active' => 1,
        ]);

        $this->assertCount(1, $topics = Topic::all());

        $topic = $topics->first();

        $this->assertEquals($user->id, $topic->user_id);
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

    public function test_user_cannot_update_topic_without_category()
    {
        $user = factory(User::class)->create();
        $category = factory(Category::class)->create();
        $topic = factory(Topic::class)->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Topic One',
            'content' => 'This is a test text one',
            'active' => 1,
        ]);

        $response = $this->actingAs($user)->from($this->topicEditGetRoute($topic->id))->put($this->topicUpdateRoute($topic->id), [
            'category_id' => '',
            'title' => 'Topic Two',
            'content' => 'This is a test text two',
            'active' => 1,
        ]);

        $this->assertCount(1, $topics = Topic::all());

        $topic = $topics->first();

        $this->assertEquals($user->id, $topic->user_id);
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

    public function test_user_cannot_update_topic_without_title()
    {
        $user = factory(User::class)->create();
        $category = factory(Category::class)->create();
        $topic = factory(Topic::class)->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Topic One',
            'content' => 'This is a test text one',
            'active' => 1,
        ]);

        $otherCategory = factory(Category::class)->create();

        $response = $this->actingAs($user)->from($this->topicEditGetRoute($topic->id))->put($this->topicUpdateRoute($topic->id), [
            'category_id' => $otherCategory->id,
            'title' => '',
            'content' => 'This is a test text two',
            'active' => 1,
        ]);

        $this->assertCount(1, $topics = Topic::all());

        $topic = $topics->first();

        $this->assertEquals($user->id, $topic->user_id);
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

    public function test_user_cannot_update_topic_without_content()
    {
        $user = factory(User::class)->create();
        $category = factory(Category::class)->create();
        $topic = factory(Topic::class)->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Topic One',
            'content' => 'This is a test text one',
            'active' => 1,
        ]);

        $otherCategory = factory(Category::class)->create([
            'id' => 2
        ]);

        $response = $this->actingAs($user)->from($this->topicEditGetRoute($topic->id))->put($this->topicUpdateRoute($topic->id), [
            'category_id' => $otherCategory->id,
            'title' => 'Topic Two',
            'content' => '',
            'active' => 1,
        ]);

        $this->assertCount(1, $topics = Topic::all());

        $topic = $topics->first();

        $this->assertEquals($user->id, $topic->user_id);
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

    public function test_user_cannot_update_topic_without_situation()
    {
        $user = factory(User::class)->create();
        $category = factory(Category::class)->create();
        $topic = factory(Topic::class)->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Topic One',
            'content' => 'This is a test text one',
            'active' => 1,
        ]);

        $otherCategory = factory(Category::class)->create([
            'id' => 2
        ]);

        $response = $this->actingAs($user)->from($this->topicEditGetRoute($topic->id))->put($this->topicUpdateRoute($topic->id), [
            'category_id' => $otherCategory->id,
            'title' => 'Topic Two',
            'content' => 'This is a test text two',
            'active' => '',
        ]);

        $this->assertCount(1, $topics = Topic::all());

        $topic = $topics->first();

        $this->assertEquals($user->id, $topic->user_id);
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

    public function test_user_can_delete_a_category()
    {
        $user = factory(User::class, 'admin')->create();
        $topic = factory(Topic::class)->create([
            'user_id' => $user->id
        ]);

        $response = $this->actingAs($user)->from($this->topicIndexGetRoute())->delete($this->topicDeleteRoute($topic->id));

        $this->assertCount(0, Topic::all());
        $response->assertRedirect($this->topicIndexGetRoute());
        $response->assertSessionHas('message', [
            'type' => 'success',
            'message' => 'Topic deleted successfully.',
        ]);
    }
}
