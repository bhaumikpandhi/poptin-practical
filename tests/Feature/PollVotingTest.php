<?php

namespace Tests\Feature;

use App\Enums\RoleEnum;
use App\Models\Poll;
use App\Models\PollOption;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class PollVotingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::create(['name' => RoleEnum::Admin->value]);
        Role::create(['name' => RoleEnum::User->value]);
    }

    public function test_guest_can_vote_once_per_ip()
    {
        $poll = Poll::factory()->create();
        $options = PollOption::factory()->count(2)->create(['poll_id' => $poll->id]);

        $url = route('polls.vote', $poll);

        $this->withServerVariables(['REMOTE_ADDR' => '1.2.3.4'])
            ->post($url, ['option' => $options[0]->id])
            ->assertSessionHas('success');

        $this->withServerVariables(['REMOTE_ADDR' => '1.2.3.4'])
            ->post($url, ['option' => $options[1]->id])
            ->assertSessionHasErrors();

        $this->assertDatabaseCount('poll_votes', 1);
    }

    public function test_logged_in_user_can_vote_and_not_twice()
    {
        $user = User::factory()->create();
        $user->assignRole(RoleEnum::User->value);

        $poll = Poll::factory()->create();
        $options = PollOption::factory()->count(2)->create(['poll_id' => $poll->id]);

        $this->actingAs($user)
            ->post(route('polls.vote', $poll), ['option' => $options[1]->id])
            ->assertSessionHas('success');

        $this->actingAs($user)
            ->post(route('polls.vote', $poll), ['option' => $options[0]->id])
            ->assertSessionHasErrors();

        $this->assertDatabaseHas('poll_votes', [
            'user_id' => $user->id,
            'poll_option_id' => $options[1]->id,
        ]);
        $this->assertDatabaseCount('poll_votes', 1);
    }

    public function test_logged_in_user_blocked_by_guest_vote_same_ip()
    {
        $user = User::factory()->create();
        $user->assignRole(RoleEnum::User->value);

        $poll = Poll::factory()->create();
        $options = PollOption::factory()->count(2)->create(['poll_id' => $poll->id]);

        // Guest votes first from IP
        $this->withServerVariables(['REMOTE_ADDR' => '9.8.7.6'])
            ->post(route('polls.vote', $poll), ['option' => $options[0]->id])
            ->assertSessionHas('success');
        
        // Same IP user logs in and tries to vote - should be blocked
        $this->actingAs($user)
            ->withServerVariables(['REMOTE_ADDR' => '9.8.7.6'])
            ->post(route('polls.vote', $poll), ['option' => $options[1]->id])
            ->assertSessionHasErrors();

        $this->assertDatabaseCount('poll_votes', 1);
    }

    public function test_guest_later_as_user_different_ip_can_vote()
    {
        $user = User::factory()->create();
        $user->assignRole(RoleEnum::User->value);

        $poll = Poll::factory()->create();
        $options = PollOption::factory()->count(2)->create(['poll_id' => $poll->id]);

        // Guest votes from IP 1
        $this->withServerVariables(['REMOTE_ADDR' => '9.8.7.6'])
            ->post(route('polls.vote', $poll), ['option' => $options[0]->id])
            ->assertSessionHas('success');
        $this->actingAs($user)
            ->withServerVariables(['REMOTE_ADDR' => '1.1.1.1'])
            ->post(route('polls.vote', $poll), ['option' => $options[1]->id])
            ->assertSessionHas('success');

        $this->assertDatabaseCount('poll_votes', 2);
    }

    public function test_admin_cannot_vote()
    {
        $admin = User::factory()->create();
        $admin->assignRole(RoleEnum::Admin->value);

        $poll = Poll::factory()->create();
        $options = PollOption::factory()->count(1)->create(['poll_id' => $poll->id]);

        $this->actingAs($admin)
            ->post(route('polls.vote', $poll), ['option' => $options[0]->id])
            ->assertForbidden();

        $this->assertDatabaseCount('poll_votes', 0);
    }

    public function test_cannot_vote_for_option_from_different_poll()
    {
        $poll1 = Poll::factory()->create();
        $poll2 = Poll::factory()->create();
        
        $options1 = PollOption::factory()->count(2)->create(['poll_id' => $poll1->id]);
        $options2 = PollOption::factory()->count(2)->create(['poll_id' => $poll2->id]);

        $this->withServerVariables(['REMOTE_ADDR' => '1.2.3.4'])
            ->post(route('polls.vote', $poll1), ['option' => $options2[0]->id])
            ->assertSessionHasErrors();

        $this->assertDatabaseCount('poll_votes', 0);
    }}