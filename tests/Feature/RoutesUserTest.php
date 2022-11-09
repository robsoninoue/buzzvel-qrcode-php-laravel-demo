<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoutesUserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_api_root()
    {
        $response = $this->get('/api');

        $response->assertStatus(404);
    }

    public function test_create_user_missing_data()
    {
        $response = $this->postJson('/api/user', [
        ]);

        $response->assertStatus(422);
    }

    public function test_create_user()
    {
        $response = $this->postJson('/api/user', [
            'name' => 'Teste 123',
            'email' => 'teste@123.com',
            'tel' => '123 456 789',
            'linkedin' => 'linkedin',
            'github' => 'github'
        ]);
        
        $response->assertStatus(201)->assertJson(['profile' => 'Teste123']);
    }
    
    public function test_create_repeated_user()
    {
        $this->postJson('/api/user', [
            'name' => 'Teste 123',
            'email' => 'teste@123.com',
            'tel' => '123 456 789',
            'linkedin' => 'linkedin',
            'github' => 'github'
        ]);

        $response = $this->postJson('/api/user', [
            'name' => 'Teste 123',
            'email' => 'teste@123.com',
            'tel' => '123 456 789',
            'linkedin' => 'linkedin',
            'github' => 'github'
        ]);

        $response->assertStatus(201)->assertJson(['profile' => 'Teste1230']);
    }

    public function test_create_user_with_strange_name(){
        $this->postJson('/api/user', [
            'name' => 'Teste @@@@@123!@<>',
            'email' => 'teste@123.com',
            'tel' => '123 456 789',
            'linkedin' => 'linkedin',
            'github' => 'github'
        ]);

        $response = $this->get('/api/users/Teste123');

        $response->assertStatus(200)->assertJson([
            'full_name' => 'Teste 123',
            'email' => 'teste@123.com',
            'tel' => '123 456 789',
            'linkedin_url' => 'linkedin',
            'github_url' => 'github',
            'profile' => 'Teste123'
        ]);
    }

    public function test_show_user()
    {
        $this->postJson('/api/user', [
            'name' => 'Teste 123',
            'email' => 'teste@123.com',
            'tel' => '123 456 789',
            'linkedin' => 'linkedin',
            'github' => 'github'
        ]);

        $response = $this->get('/api/users/Teste123');

        $response->assertStatus(200)->assertJson([
            'full_name' => 'Teste 123',
            'email' => 'teste@123.com',
            'tel' => '123 456 789',
            'linkedin_url' => 'linkedin',
            'github_url' => 'github',
            'profile' => 'Teste123'
        ]);
    }

    public function test_show_empty_user()
    {
        $response = $this->get('/api/users/');
        
        $response->assertStatus(404);
    }

    public function test_show_wrong_user()
    {
        $response = $this->get('/api/users/wrong');
        
        $response->assertStatus(404);
    }

}
