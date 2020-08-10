<?php

namespace Tests\Feature\Http\Controllers\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;


    /** test */

    public function register_displays_the_registration_form()
    {
        $response = $this->get(route('register'));
    
        $response->assertStatus(200);
        $response->assertViewIs('auth.register');
    }

    /** @test */
    public function registration_displays_validation_errors()
    {
        $response = $this->post('/register', []);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function registration_authenticates_and_redirects_user()
    {
        $user = factory(User::class)->create();

        $response = $this->post(route('register'), [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'password' => 'password',
            'contact' => $user->contact
        ]);
    
        $response->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($user);

    }

    /** @test */
    public function register_creates_and_authenticates_a_user()
    {
        $first_name = $this->faker->first_name(2,30);
        $last_name = $this->faker->last_name(2,30);
        $email = $this->faker->safeEmail;
        $password = $this->faker->password(8,128);
        $contact = $this->faker->contact(8,10);

        $response = $this->post('register', [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password,
            'contact' => $user->contact
        ]);

        $response->assertRedirect(route('home'));

        $user = User::where('email', $email)->where('first_name', $first_name)->first();
        $this->assertNotNull($user);

        $this->assertAuthenticatedAs($user);
    }

    
}
