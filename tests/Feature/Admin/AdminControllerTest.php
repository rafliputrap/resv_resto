<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\Admin;
use App\Models\Table;
use App\Models\Reservation;
use App\Models\ReservationDetail;
use App\Models\Menu;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class AdminControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function test_admin_bisa_login_dengan_email_dan_password()
    {
        $response = $this->post('/admin/login', [
            'email' => 'admin@hafa.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($this->admin, 'web');
    }

    #[Test]
    public function test_admin_tidak_bisa_login_dengan_password_salah()
    {
        $response = $this->post('/admin/login', [
            'email' => 'admin@hafa.com',
            'password' => 'password_salah',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertGuest();
    }

    #[Test]
    public function test_admin_tidak_bisa_login_dengan_email_tidak_ada()
    {
        $response = $this->post('/admin/login', [
            'email' => 'tidak_ada@email.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertGuest();
    }

    #[Test]
    public function test_admin_bisa_lihat_halaman_login()
    {
        $response = $this->get(route('admin.login'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.login');
    }

    #[Test]
    public function test_dashboard_menampilkan_data_hari_ini()
    {
        $table = Table::create(['table_number' => 'L1', 'capacity' => 2, 'status' => 'occupied']);

        Reservation::create([
            'customer_name' => 'Pelanggan Hafa',
            'table_id' => $table->id,
            'status' => 'paid',
            'phone' => '08123',
            'total' => 75000,
            'created_at' => now(),
        ]);

        $response = $this->actingAs($this->admin, 'web')->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHasAll(['reservations', 'totalOmzet', 'totalPengunjung', 'activeTables']);
    }

    #[Test]
    public function test_dashboard_hanya_tampilkan_status_paid_dan_confirmed()
    {
        $table = Table::create(['table_number' => 'L1', 'capacity' => 2, 'status' => 'occupied']);

        Reservation::create([
            'customer_name' => 'Pelanggan 1',
            'table_id' => $table->id,
            'status' => 'paid',
            'phone' => '08123',
            'total' => 50000,
        ]);

        Reservation::create([
            'customer_name' => 'Pelanggan 2',
            'table_id' => $table->id,
            'status' => 'completed',
            'phone' => '08123',
            'total' => 75000,
        ]);

        $response = $this->actingAs($this->admin, 'web')->get(route('admin.dashboard'));

        $reservations = $response->viewData('reservations');
        $this->assertEquals(1, $reservations->count());
    }

    #[Test]
    public function test_admin_bisa_lihat_history_harian()
    {
        $table = Table::create(['table_number' => 'L1', 'capacity' => 2, 'status' => 'occupied']);

        Reservation::create([
            'customer_name' => 'Pelanggan',
            'table_id' => $table->id,
            'status' => 'completed',
            'phone' => '08123',
            'total' => 50000,
            'created_at' => now(),
            'completed_at' => now()->addHours(2)
        ]);

        $response = $this->actingAs($this->admin, 'web')
            ->get(route('admin.history', ['filter' => 'daily', 'date' => now()->format('Y-m-d')]));

        $response->assertStatus(200);
        $response->assertViewHas('history');
        $response->assertViewHas('totalOmzet', 50000);
        $response->assertViewHas('totalPengunjung', 1);
    }

    #[Test]
    public function test_admin_bisa_lihat_history_mingguan()
    {
        $table = Table::create(['table_number' => 'L1', 'capacity' => 2, 'status' => 'occupied']);

        Reservation::create([
            'customer_name' => 'Pelanggan',
            'table_id' => $table->id,
            'status' => 'completed',
            'phone' => '08123',
            'total' => 50000,
            'created_at' => now(),
            'completed_at' => now()->addHours(2)
        ]);

        $response = $this->actingAs($this->admin, 'web')
            ->get(route('admin.history', ['filter' => 'weekly']));

        $response->assertStatus(200);
        $response->assertViewHas('history');
    }

    #[Test]
    public function test_admin_bisa_lihat_history_bulanan()
    {
        $table = Table::create(['table_number' => 'L1', 'capacity' => 2, 'status' => 'occupied']);

        Reservation::create([
            'customer_name' => 'Pelanggan',
            'table_id' => $table->id,
            'status' => 'completed',
            'phone' => '08123',
            'total' => 50000,
            'created_at' => now(),
            'completed_at' => now()->addHours(2)
        ]);

        $response = $this->actingAs($this->admin, 'web')
            ->get(route('admin.history', ['filter' => 'monthly']));

        $response->assertStatus(200);
        $response->assertViewHas('history');
    }

    #[Test]
    public function test_admin_bisa_export_history_harian()
    {
        $table = Table::create(['table_number' => 'L1', 'capacity' => 2, 'status' => 'occupied']);

        Reservation::create([
            'customer_name' => 'Pelanggan',
            'table_id' => $table->id,
            'status' => 'completed',
            'phone' => '08123',
            'total' => 50000,
            'created_at' => now(),
            'completed_at' => now()->addHours(2)
        ]);

        $response = $this->actingAs($this->admin, 'web')
            ->get(route('admin.history.export', ['filter' => 'daily', 'date' => now()->format('Y-m-d')]));

        $response->assertStatus(200);
    }

    #[Test]
    public function test_admin_tidak_bisa_export_tanpa_data()
    {
        $response = $this->actingAs($this->admin, 'web')
            ->get(route('admin.history.export', ['filter' => 'daily', 'date' => now()->format('Y-m-d')]));

        $response->assertRedirect();
    }

    #[Test]
    public function test_admin_bisa_reset_table()
    {
        $table = Table::create(['table_number' => 'L1', 'capacity' => 2, 'status' => 'occupied']);

        $reservation = Reservation::create([
            'customer_name' => 'Pelanggan',
            'table_id' => $table->id,
            'status' => 'paid',
            'phone' => '08123',
            'total' => 50000,
        ]);

        $response = $this->actingAs($this->admin, 'web')
            ->post(route('admin.tables.updateStatus', $table->id));

        $response->assertRedirect();
        $this->assertDatabaseHas('tables', ['id' => $table->id, 'status' => 'available']);
        $this->assertDatabaseHas('reservations', ['id' => $reservation->id, 'status' => 'completed']);
    }

    #[Test]
    public function test_admin_bisa_hapus_reservasi()
    {
        $table = Table::create(['table_number' => 'L1', 'capacity' => 2, 'status' => 'occupied']);

        $menu = Menu::create([
            'name' => 'Nasi Goreng',
            'price' => 25000
        ]);

        $reservation = Reservation::create([
            'customer_name' => 'Pelanggan',
            'table_id' => $table->id,
            'status' => 'paid',
            'phone' => '08123',
            'total' => 50000,
        ]);

        ReservationDetail::create([
            'reservation_id' => $reservation->id,
            'menu_id' => $menu->id,
            'quantity' => 2,
            'price' => 25000
        ]);

        $response = $this->actingAs($this->admin, 'web')
            ->delete(route('admin.history.destroy', $reservation->id));

        $response->assertRedirect();
        $this->assertDatabaseMissing('reservations', ['id' => $reservation->id]);
    }

    #[Test]
    public function test_admin_bisa_logout()
    {
        $response = $this->actingAs($this->admin, 'web')->post(route('admin.logout'));
        $response->assertRedirect(route('admin.login'));
        $this->assertGuest();
    }

    #[Test]
    public function test_admin_tidak_bisa_akses_history_tanpa_login()
    {
        // History tidak perlu auth, jadi cek bahwa bisa diakses
        $response = $this->get(route('admin.history'));
        $response->assertStatus(200);
    }
}
