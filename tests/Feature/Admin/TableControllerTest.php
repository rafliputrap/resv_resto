<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\Admin;
use App\Models\Table;
use App\Models\Reservation;
use App\Models\TableLayout;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;

class TableControllerTest extends TestCase
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
    public function test_admin_bisa_lihat_daftar_meja()
    {
        Table::create([
            'table_number' => 'L1',
            'capacity' => 4,
            'status' => 'available'
        ]);

        $response = $this->actingAs($this->admin, 'web')->get(route('admin.tables.index'));

        $response->assertStatus(200);
        $response->assertViewHas('tables');
        $response->assertViewIs('admin.tables');
    }

    #[Test]
    public function test_admin_bisa_tambah_meja()
    {
        $response = $this->actingAs($this->admin, 'web')->post(route('admin.tables.store'), [
            'table_number' => 'L1',
            'capacity' => 4
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tables', [
            'table_number' => 'L1',
            'capacity' => 4,
            'status' => 'available'
        ]);
    }

    #[Test]
    public function test_admin_tidak_bisa_tambah_meja_dengan_nomor_sama()
    {
        Table::create([
            'table_number' => 'L1',
            'capacity' => 4
        ]);

        $response = $this->actingAs($this->admin, 'web')->post(route('admin.tables.store'), [
            'table_number' => 'L1',
            'capacity' => 4
        ]);

        $response->assertSessionHasErrors('table_number');
    }

    #[Test]
    public function test_admin_tidak_bisa_tambah_meja_kapasitas_invalid()
    {
        $response = $this->actingAs($this->admin, 'web')->post(route('admin.tables.store'), [
            'table_number' => 'L1',
            'capacity' => 0
        ]);

        $response->assertSessionHasErrors('capacity');
    }

    #[Test]
    public function test_admin_bisa_update_status_meja_menjadi_selesai()
    {
        $table = Table::create([
            'table_number' => 'L1',
            'capacity' => 4,
            'status' => 'occupied'
        ]);

        $reservation = Reservation::create([
            'customer_name' => 'Customer',
            'table_id' => $table->id,
            'status' => 'paid',
            'phone' => '08123',
            'total' => 50000
        ]);

        $response = $this->actingAs($this->admin, 'web')->post(route('admin.tables.updateStatus', $table->id));

        $response->assertRedirect();
        $this->assertDatabaseHas('tables', [
            'id' => $table->id,
            'status' => 'available'
        ]);
        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status' => 'completed'
        ]);
    }

    #[Test]
    public function test_admin_bisa_hapus_meja()
    {
        $table = Table::create([
            'table_number' => 'L1',
            'capacity' => 4
        ]);

        $response = $this->actingAs($this->admin, 'web')->delete(route('admin.tables.destroy', $table->id));

        $response->assertRedirect();
        $this->assertDatabaseMissing('tables', ['id' => $table->id]);
    }

    #[Test]
    public function test_admin_tidak_bisa_lihat_meja_jika_belum_login()
    {
        $response = $this->get(route('admin.tables.index'));
        $response->assertStatus(200);
    }

    #[Test]
    public function test_admin_tidak_bisa_tambah_meja_jika_belum_login()
    {
        $response = $this->post(route('admin.tables.store'), [
            'table_number' => 'L1',
            'capacity' => 4
        ]);

        $response->assertRedirect('/');
    }
}
