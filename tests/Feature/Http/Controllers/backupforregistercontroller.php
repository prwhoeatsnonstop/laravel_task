<?php

namespace Tests\Feature\Http\Controllers\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;


    /** test */

    public function login_displays_the_login_form()
    {
        $response = $this->get(route('login'));
    
        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    /** @test */
    public function login_displays_validation_errors()
    {
        $response = $this->post('/login', []);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function login_authenticates_and_redirects_user()
    {
        $user = factory(User::class)->create();

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password'
        ]);
    
        $response->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($user);

    }

    /** @test */
    public function register_creates_and_authenticates_a_user()
{
    $name = $this->faker->name;
    $email = $this->faker->safeEmail;
    $password = $this->faker->password(8);

    $response = $this->post('register', [
        'name' => $name,
        'email' => $email,
        'password' => $password,
        'password_confirmation' => $password,
    ]);

    $response->assertRedirect(route('home'));

    $user = User::where('email', $email)->where('name', $name)->first();
    $this->assertNotNull($user);

    $this->assertAuthenticatedAs($user);
}

    //$user = factory(User::class)->create();

    // $response = $this->post(route('register'), [
    //     'first_name' => $user->first_name,
    //     'last_name' => $user->last_name,
    //     'email' => $user->email,
    //     'password' => 'password'
    //     'contact' => $user->contact,
    // ]);

    //$response->assertRedirect(route('home'));
    //$this->assertAuthenticatedAs($user);

    // public function register_creates_and_authenticates_a_user()
    //     {
    //         $response = $this->post('register', [
    //             'first_name' => 'Mary',
    //             'last_name' => 'Beth',
    //             'email' => 'mbeth@gmail.com',
    //             'password' => 'password',
    //             'password_confirmation' => 'password'
    //         ]);

    //         $this->assertDatabaseHas('users', [
    //             'first_name' => 'Mary',
    //             'last_name' => 'Beth',
    //             'email' => 'mbeth@gmail.com',
    //             'password' => 'password',
    //             'password_confirmation' => 'password'
    //         ]);

    //         $response->assertRedirect(route('home'));

    //     }
}
