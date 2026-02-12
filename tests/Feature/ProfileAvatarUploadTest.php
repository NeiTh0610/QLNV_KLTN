<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileAvatarUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_upload_avatar()
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $response = $this->actingAs($user)->put(route('profile.update'), [
            'avatar' => UploadedFile::fake()->image('avatar.jpg'),
        ]);

        $response->assertStatus(302);

        $user->refresh();

        $this->assertNotNull($user->avatar_path);
        Storage::disk('public')->assertExists($user->avatar_path);
    }
}
