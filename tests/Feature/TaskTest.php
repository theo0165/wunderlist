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
            'list' => $list->getHashId()
        ]);

        $response->assertRedirect('/list/' . $list->getHashId())->assertStatus(201);
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
            ->from('/list/' . $list->getHashId())
            ->post(
                '/task/' . $task->getHashId() . '/edit',
                [
                    'title' => 'Updated title',
                    'description' => 'Updated Description',
                    'deadline' => date('Y-m-d', strtotime('tomorrow')),
                    'list' => $list->getHashId(),
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
            ->from('/list/' . $task->getHashId())
            ->post(
                '/task/' . $task->getHashId() . '/edit',
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
            ->from('/list/' . $task->getHashId())
            ->get('/task/' . $task->getHashId() . '/delete');

        $response->assertRedirect('/list/' . $task->getHashId());
    }
}
