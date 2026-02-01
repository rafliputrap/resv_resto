<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Table;
use App\Models\Reservation;
use App\Models\Menu;
use App\Models\ReservationDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Support\Facades\Hash;

class ReservationControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::create([
            'name' => 'Admin Hafa',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
        ]);
    }

    #[Test]
    public function test_admin_bisa_lihat_history()
    {
        $table = Table::create(['table_number' => 'L1', 'capacity' => 2, 'status' => 'occupied']);

        Reservation::create([
            'customer_name' => 'Test User',
            'table_id' => $table->id,
            'status' => 'completed',
            'phone' => '08123',
            'total' => 50000
        ]);

        $response = $this->get(route('admin.history'));

        $response->assertStatus(200);
        $response->assertViewHas('history');
    }

    #[Test]
    public function test_admin_bisa_export_history()
    {
        $table = Table::create(['table_number' => 'L1', 'capacity' => 2, 'status' => 'occupied']);

        Reservation::create([
            'customer_name' => 'Test User',
            'table_id' => $table->id,
            'status' => 'completed',
            'phone' => '08123',
            'total' => 50000
        ]);

        $response = $this->get(route('admin.history.export'));

        $response->assertStatus(200);
    }

    #[Test]
    public function test_admin_bisa_delete_dari_history()
    {
        $table = Table::create(['table_number' => 'L1', 'capacity' => 2, 'status' => 'occupied']);

        $res = Reservation::create([
            'customer_name' => 'Test User',
            'table_id' => $table->id,
            'status' => 'completed',
            'phone' => '08123',
            'total' => 50000
        ]);

        $response = $this->actingAs($this->admin, 'web')
                         ->delete(route('admin.history.destroy', $res->id));

        $response->assertRedirect();
        $this->assertDatabaseMissing('reservations', ['id' => $res->id]);
    }
}



