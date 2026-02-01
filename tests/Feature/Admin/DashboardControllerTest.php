<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\Admin;
use App\Models\Table;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::create([
            'name' => 'Administrator',
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
        ]);
    }

    #[Test]
    public function test_dashboard_menampilkan_total_reservasi_dan_meja()
    {
        // Buat beberapa data
        Table::create(['table_number' => 'L1', 'capacity' => 2, 'status' => 'available']);
        Table::create(['table_number' => 'L2', 'capacity' => 4, 'status' => 'available']);
        
        Reservation::create([
            'customer_name' => 'User 1',
            'table_id' => 1,
            'status' => 'completed',
            'phone' => '08123',
            'total' => 50000
        ]);

        $response = $this->actingAs($this->admin, 'web')->get(route('admin.dashboard'));

        $response->assertStatus(200);
    }

    #[Test]
    public function test_dashboard_bisa_diakses_saat_sudah_login()
    {
        $response = $this->actingAs($this->admin, 'web')->get(route('admin.dashboard'));
        $response->assertStatus(200);
    }
}
