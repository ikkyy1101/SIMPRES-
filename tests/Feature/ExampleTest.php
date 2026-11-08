<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_beranda_page_renders_from_root(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('SIMPRES');
        $response->assertSee('Sistem Informasi');
        $response->assertSee('Fakultas Sains Dan Teknologi');
    }

    public function test_beranda_page_renders_from_beranda_route(): void
    {
        $response = $this->get('/beranda');

        $response->assertOk();
        $response->assertSee('SIMPRES');
    }

    public function test_guest_is_redirected_to_login_from_dashboard(): void
    {
        $this->get('/dashboard')->assertRedirect(route('login'));
    }
}
