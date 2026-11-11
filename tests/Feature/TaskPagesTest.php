<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_pages_require_login(): void
    {
        $this->get('/tasks')->assertRedirect('/login');
        $this->get('/tasks/create')->assertRedirect('/login');
    }

    public function test_empty_task_list_and_create_form_render(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get('/tasks')->assertOk()->assertSee('No tasks found.');
        $this->get('/tasks/create')->assertOk()->assertSee('name="title"', false);
    }

    public function test_task_list_filters_and_only_shows_the_current_users_tasks(): void
    {
        $user = User::factory()->create();
        $user->tasks()->create(['title' => 'Prepare interview', 'status' => 'pending']);
        $user->tasks()->create(['title' => 'Prepare report', 'status' => 'completed']);
        $user->tasks()->create(['title' => 'Read notes', 'status' => 'pending']);
        User::factory()->create()->tasks()->create(['title' => 'Prepare private task', 'status' => 'pending']);

        $this->actingAs($user)->get('/tasks?search=Prepare&status=pending')
            ->assertOk()
            ->assertSee('Prepare interview')
            ->assertDontSee('Prepare report')
            ->assertDontSee('Read notes')
            ->assertDontSee('Prepare private task')
            ->assertSee('Pending: 2');
    }

    public function test_tasks_can_be_created_edited_and_deleted(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $data = [
            'title' => 'Prepare interview',
            'description' => 'Review Laravel',
            'due_date' => '2026-11-10',
            'status' => 'pending',
        ];

        $this->post('/tasks', $data)->assertRedirect('/tasks');
        $task = $user->tasks()->sole();

        $this->get('/tasks')->assertOk()->assertSee('Prepare interview')->assertSee('Review Laravel');
        $this->get("/tasks/{$task->id}/edit")->assertOk()
            ->assertSee('value="2026-11-10"', false)->assertSee('Review Laravel');

        $this->put("/tasks/{$task->id}", array_replace($data, ['status' => 'completed']))
            ->assertRedirect('/tasks');
        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'user_id' => $user->id, 'status' => 'completed']);

        $this->delete("/tasks/{$task->id}")->assertRedirect('/tasks');
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_task_form_displays_validation_errors_and_preserves_input(): void
    {
        $this->actingAs(User::factory()->create());

        $this->from('/tasks/create')->post('/tasks', [
            'description' => 'Keep these notes',
            'status' => 'pending',
        ])->assertRedirect('/tasks/create')->assertSessionHasErrors('title');

        $this->get('/tasks/create')->assertOk()
            ->assertSee('The title field is required.')->assertSee('Keep these notes');
    }
}
