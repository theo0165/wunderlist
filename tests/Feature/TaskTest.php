<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\TodoList;
use App\Models\User;
use Auth;
use Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Str;
use Tests\TestCase;
use Vinkla\Hashids\Facades\Hashids;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_task()
    {
        $user = User::create([
            'email' => Str::random() . '@example.com',
            'name' => 'Test User',
            'password' => Hash::make('1234567890')
        ]);

        Auth::loginUsingId($user->id);

        $list = TodoList::create([
            'title' => "Test list"
        ]);

        $response = $this->actingAs($user)->post('/task/new', [
            'title' => 'Test task',
            'description' => 'Test task description',
            'deadline' => date('Y-m-d', strtotime('tomorrow')),
            'list' => Hashids::encode($list->id)
        ]);

        $response->assertRedirect('/list/' . Hashids::encode($list->id))->assertStatus(201);
    }

    public function test_can_edit_task()
    {
        $user = User::create([
            'email' => Str::random() . '@example.com',
            'name' => 'Test User',
            'password' => Hash::make('1234567890')
        ]);

        Auth::loginUsingId($user->id);

        $list = TodoList::create([
            'title' => "Test list"
        ]);

        $task = Task::create([
            'title' => 'Test task',
            'description' => 'Test task description',
            'deadline' => date('Y-m-d', strtotime('tomorrow')),
            'list_id' => $list->id
        ]);

        $response = $this->actingAs($user)
            ->from('/list/' . Hashids::encode($list->id))
            ->post(
                '/task/' . Hashids::encode($task->id) . '/edit',
                [
                    'title' => 'Updated title',
                    'description' => 'Updated Description',
                    'deadline' => date('Y-m-d', strtotime('tomorrow')),
                    'list' => Hashids::encode($list->id),
                    'function' => 'edit'
                ]
            );

        $response->assertRedirect('/list/' . Hashids::encode($list->id));
    }

    public function test_can_complete_task()
    {
        $user = User::create([
            'email' => Str::random() . '@example.com',
            'name' => 'Test User',
            'password' => Hash::make('1234567890')
        ]);

        Auth::loginUsingId($user->id);

        $list = TodoList::create([
            'title' => "Test list"
        ]);

        $task = Task::create([
            'title' => 'Test task',
            'description' => 'Test task description',
            'deadline' => date('Y-m-d', strtotime('tomorrow')),
            'list_id' => $list->id
        ]);

        $response = $this->actingAs($user)
            ->from('/list/' . Hashids::encode($task->id))
            ->post(
                '/task/' . Hashids::encode($task->id) . '/edit',
                [
                    'completed' => 'on',
                    'function' => 'complete'
                ]
            );

        $response->assertStatus(202);
    }

    public function test_can_delete_task()
    {
        $user = User::create([
            'email' => Str::random() . '@example.com',
            'name' => 'Test User',
            'password' => Hash::make('1234567890')
        ]);

        Auth::loginUsingId($user->id);

        $list = TodoList::create([
            'title' => "Test list"
        ]);

        $task = Task::create([
            'title' => 'Test task',
            'description' => 'Test task description',
            'deadline' => date('Y-m-d', strtotime('tomorrow')),
            'list_id' => $list->id
        ]);

        $response = $this->actingAs($user)
            ->from('/list/' . Hashids::encode($task->id))
            ->get('/task/' . Hashids::encode($task->id) . '/delete');

        $response->assertRedirect('/list/' . Hashids::encode($task->id));
    }
}
