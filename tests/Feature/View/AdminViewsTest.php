<?php

namespace Tests\Feature\View;

use Tests\TestCase;

class AdminViewsTest extends TestCase
{
    #[\PHPUnit\Framework\Attributes\Test]
    public function test_admin_login_page_renders()
    {
        $response = $this->get('/admin/login');
        $response->assertStatus(200);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_admin_logout_post_route()
    {
        $response = $this->post('/admin/logout');
        $response->assertStatus(302);
    }
}




