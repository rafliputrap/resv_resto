<?php

namespace Tests\Unit;

use App\Views\UserView;
use PHPUnit\Framework\TestCase;

class UserViewTest extends TestCase
{
    private UserView $view;

    protected function setUp(): void
    {
        parent::setUp();
        $this->view = new UserView();
    }

    public function test_render_home_returns_array()
    {
        $result = $this->view->renderHome();
        $this->assertIsArray($result);
        $this->assertArrayHasKey('title', $result);
        $this->assertArrayHasKey('description', $result);
    }

    public function test_render_home_has_correct_title()
    {
        $result = $this->view->renderHome();
        $this->assertEquals('Toko Online', $result['title']);
    }

    public function test_render_menu_returns_array_with_menus()
    {
        $result = $this->view->renderMenu([]);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('menus', $result);
        $this->assertArrayHasKey('cart', $result);
        $this->assertArrayHasKey('cartCount', $result);
    }

    public function test_render_menu_counts_cart_items()
    {
        $cart = [
            ['id' => 1, 'name' => 'Nasi Goreng', 'price' => 25000],
            ['id' => 2, 'name' => 'Mie Goreng', 'price' => 20000],
        ];
        $result = $this->view->renderMenu([], $cart);
        $this->assertEquals(2, $result['cartCount']);
    }

    public function test_render_order_detail_returns_array()
    {
        $result = $this->view->renderOrderDetail([], 75000);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('items', $result);
        $this->assertArrayHasKey('total', $result);
        $this->assertArrayHasKey('formattedTotal', $result);
    }

    public function test_render_order_detail_formats_total()
    {
        $result = $this->view->renderOrderDetail([], 75000);
        $this->assertEquals('75.000', $result['formattedTotal']);
    }

    public function test_render_payment_returns_array()
    {
        $result = $this->view->renderPayment(null, 'https://payment.com');
        $this->assertIsArray($result);
        $this->assertArrayHasKey('reservation', $result);
        $this->assertArrayHasKey('paymentUrl', $result);
    }
}
