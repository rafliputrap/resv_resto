<?php

namespace Tests\Feature\View;

use Tests\TestCase;

class UserViewsTest extends TestCase
{
    #[\PHPUnit\Framework\Attributes\Test]
    public function test_user_home_page_renders()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_user_new_session_redirects()
    {
        $response = $this->get('/new-session');
        $response->assertStatus(302);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_user_order_detail_page()
    {
        $response = $this->get('/order-detail');
        $response->assertStatus(302);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_user_menu_page_requires_session()
    {
        $response = $this->get('/menu');
        $response->assertStatus(302);
    }
}


