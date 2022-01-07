<?php

namespace Tests\Feature;

use App\Models\TodoList;
use App\Models\User;
use Auth;
use Database\Factories\UserFactory;
use Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Str;
use Tests\TestCase;
use Vinkla\Hashids\Facades\Hashids;

class ListTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_list()
    {
        $user = User::create([
            'email' => Str::random() . '@example.com',
            'name' => 'Test User',
            'password' => Hash::make('1234567890')
        ]);

        Auth::loginUsingId($user->id);

        $response = $this->actingAs($user)->post('/list/new', [
            'title' => 'Test list'
        ]);

        $response->assertStatus(201)->assertRedirectContains('list');
    }

    public function test_can_edit_list()
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

        $response = $this->actingAs($user)
            ->from('/list/' . Hashids::encode($list->id))
            ->patch(
                '/list/' . Hashids::encode($list->id) . '/edit',
                [
                    'title' => 'Updated title'
                ]
            );

        $response->assertRedirect('/list/' . Hashids::encode($list->id));
    }

    public function test_can_delete_list()
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

        $response = $this->actingAs($user)
            ->from('/list/' . Hashids::encode($list->id))
            ->get('/list/' . Hashids::encode($list->id) . '/delete');

        $response->assertRedirect('/');
    }
}
