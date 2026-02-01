<?php

namespace Tests\Unit;

use App\Views\AdminView;
use PHPUnit\Framework\TestCase;

class AdminViewTest extends TestCase
{
    private AdminView $view;

    protected function setUp(): void
    {
        parent::setUp();
        $this->view = new AdminView();
    }

    public function test_render_login_returns_array()
    {
        $result = $this->view->renderLogin();
        $this->assertIsArray($result);
        $this->assertArrayHasKey('title', $result);
        $this->assertArrayHasKey('description', $result);
    }

    public function test_render_login_has_correct_title()
    {
        $result = $this->view->renderLogin();
        $this->assertEquals('Admin Login', $result['title']);
    }

    public function test_render_dashboard_returns_array_with_data()
    {
        $result = $this->view->renderDashboard([], 100000, 5, []);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('reservations', $result);
        $this->assertArrayHasKey('totalOmzet', $result);
        $this->assertArrayHasKey('totalPengunjung', $result);
        $this->assertArrayHasKey('activeTables', $result);
    }

    public function test_render_dashboard_contains_values()
    {
        $result = $this->view->renderDashboard([], 100000, 5, []);
        $this->assertEquals(100000, $result['totalOmzet']);
        $this->assertEquals(5, $result['totalPengunjung']);
    }

    public function test_render_history_returns_array()
    {
        $result = $this->view->renderHistory([], 50000, 3);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('history', $result);
        $this->assertArrayHasKey('totalOmzet', $result);
        $this->assertArrayHasKey('totalPengunjung', $result);
    }
}
