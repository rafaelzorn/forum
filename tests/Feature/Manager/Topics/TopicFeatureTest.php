<?php

namespace Tests\Feature\Manager\Topics;

use App\Forum\Topic\Models\Topic;
use Tests\TestCase;

class TopicFeatureTest extends TestCase
{
    public function test_list_all_topics()
    {
        $topics = factory(Topic::class, 3)->create();

        $response = $this->actingAs($this->admin, 'web')
            ->get(route('manager.topics.index'))
            ->assertStatus(200)
            ->assertViewHas(['currentPage', 'topics']);

        $topics->each(function($topic) use ($response) {
            $response->assertSee($topic->category->name);
            $response->assertSee($topic->title);
            $response->assertSee($topic->active);
        });
    }

    public function test_show_create_topic_page()
    {
        $this->actingAs($this->user, 'web')
            ->get(route('manager.topics.create'))
            ->assertStatus(200)
            ->assertViewHas(['currentPage', 'edit', 'topic', 'categories'])
            ->assertSee('Select the category')
            ->assertSee('Title')
            ->assertSee('Content')
            ->assertSee('Select the situation')
            ->assertSee('Save')
            ->assertSee('Return');
    }

    public function test_store_topic_successful()
    {
        $data = [
            'category_id' => $this->category->id,
            'title' => $this->faker->name,
            'content' => $this->faker->text,
            'active' => 1
        ];

        $this->actingAs($this->user, 'web')
            ->post(route('manager.topics.store'), $data)
            ->assertStatus(302)
            ->assertRedirect(route('manager.topics.index'))
            ->assertSessionHas('message', [
                'type' => 'success',
                'message' => 'Topic successfully registered.'
            ]);
    }

    public function test_errors_topic_without_completed_fields()
    {
        $this->actingAs($this->user, 'web')
            ->post(route('manager.topics.store'), [])
            ->assertSessionHasErrors([
                'category_id' => 'The category field is required.',
                'title'       => 'The title field is required.',
                'content'     => 'The content field is required.',
                'active'      => 'The active field is required.'
            ]);
    }

    public function test_show_edit_page_topic()
    {
        $topic = factory(Topic::class)->create([
            'user_id' => $this->user
        ]);

        $this->actingAs($this->user, 'web')
            ->get(route('manager.topics.edit', $topic->id))
            ->assertStatus(200)
            ->assertViewHas(['currentPage', 'edit', 'topic', 'categories'])
            ->assertSee($topic->title);
    }

    public function test_update_topic_successful()
    {
        $topic = factory(Topic::class)->create();

        $data = [
            'category_id' => $this->category->id,
            'title' => $this->faker->name,
            'content' => $this->faker->text,
            'active' => 1
        ];

        $this->actingAs($this->user, 'web')
            ->put(route('manager.topics.update', $topic->id), $data)
            ->assertStatus(302)
            ->assertRedirect(route('manager.topics.index'))
            ->assertSessionHas('message', [
                'type' => 'success',
                'message' => 'Topic successfully updated.'
            ]);
    }

    public function test_destroy_topic_successful()
    {
        $topic = factory(Topic::class)->create();

        $this->actingAs($this->user, 'web')
            ->delete(route('manager.topics.destroy', $topic->id))
            ->assertStatus(302)
            ->assertRedirect(route('manager.topics.index'))
            ->assertSessionHas('message', [
                'type' => 'success',
                'message' => 'Topic deleted successfully.'
            ]);
    }
}
