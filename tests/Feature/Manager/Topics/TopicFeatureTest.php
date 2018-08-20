<?php

namespace Tests\Feature\Manager\Topics;

use App\Forum\Topic\Models\Topic;
use Tests\TestCase;

class TopicFeatureTest extends TestCase
{
    public function test_show_the_index_topic_page()
    {
        $this->actingAs($this->user, 'web')
            ->get(route('manager.topics.index'))
            ->assertStatus(200)
            ->assertViewHas(['currentPage', 'topics']);
    }

    public function test_list_all_the_topics()
    {
        $topic = factory(Topic::class)->create();

        $this->actingAs($this->user, 'web')
            ->get(route('manager.topics.index'))
            ->assertStatus(200)
            ->assertSee($topic->category->name)
            ->assertSee($topic->title)
            ->assertSee($topic->active);
    }

    public function test_show_the_create_topic_page()
    {
        $this->actingAs($this->user, 'web')
            ->get(route('manager.topics.create'))
            ->assertStatus(200)
            ->assertViewHas(['currentPage', 'edit', 'topic', 'categories']);
    }

    public function test_show_the_topics_form()
    {
        $this->actingAs($this->user, 'web')
            ->get(route('manager.topics.create'))
            ->assertStatus(200)
            ->assertSee('Select the category')
            ->assertSee('Title')
            ->assertSee('Content')
            ->assertSee('Select the situation')
            ->assertSee('Save')
            ->assertSee('Return');
    }

    public function test_if_store_topic_successful()
    {
        $data = [
            'category_id' => $this->category->id,
            'title' => 'Título de teste',
            'content' => 'Conteúdo de teste.',
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

    public function test_show_the_edit_topic_page()
    {
        $topic = factory(Topic::class)->create();

        $this->actingAs($this->user, 'web')
            ->get(route('manager.topics.edit', $topic->id))
            ->assertStatus(200)
            ->assertViewHas(['currentPage', 'edit', 'topic', 'categories'])
            ->assertSee($topic->title);
    }

    public function test_if_update_topic_successful()
    {
        $topic = factory(Topic::class)->create();

        $data = [
            'category_id' => $this->category->id,
            'title' => 'Título de teste',
            'content' => 'Conteúdo de teste.',
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

    public function test_if_destroy_topic_successful()
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
