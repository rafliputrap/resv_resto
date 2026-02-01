<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Table;
use App\Models\Menu;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function test_user_bisa_akses_halaman_tanya_meja()
    {
        $response = $this->get(route('ask.table'));
        $response->assertStatus(200);
        $response->assertViewIs('user.ask-table');
    }

    #[Test]
    public function test_user_bisa_lihat_pilihan_meja()
    {
        Table::create(['table_number' => 'L1', 'capacity' => 4, 'status' => 'available']);

        $response = $this->get(route('select.table'));
        $response->assertStatus(200);
        $response->assertViewIs('user.select-table');
        $response->assertViewHas('tables');
    }

    #[Test]
    public function test_user_bisa_pilih_meja_tersedia_untuk_order()
    {
        $table = Table::create(['table_number' => 'L1', 'capacity' => 4, 'status' => 'available']);

        $response = $this->post(route('confirm.table'), [
            'table_id' => $table->id,
            'mode' => 'order'
        ]);

        $response->assertRedirect(route('user.menu'));
        $this->assertEquals($table->id, session('table_id'));
    }

    #[Test]
    public function test_user_tidak_bisa_pilih_meja_terisi_mode_order()
    {
        $table = Table::create(['table_number' => 'L1', 'capacity' => 4, 'status' => 'occupied']);

        $response = $this->post(route('confirm.table'), [
            'table_id' => $table->id,
            'mode' => 'order'
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    #[Test]
    public function test_user_bisa_pilih_meja_terisi_untuk_reorder()
    {
        $table = Table::create(['table_number' => 'L1', 'capacity' => 4, 'status' => 'occupied']);

        $response = $this->post(route('confirm.table'), [
            'table_id' => $table->id,
            'mode' => 'reorder'
        ]);

        $response->assertRedirect(route('user.menu'));
        $this->assertEquals($table->id, session('table_id'));
    }

    #[Test]
    public function test_user_tidak_bisa_pilih_meja_tersedia_mode_reorder()
    {
        $table = Table::create(['table_number' => 'L1', 'capacity' => 4, 'status' => 'available']);

        $response = $this->post(route('confirm.table'), [
            'table_id' => $table->id,
            'mode' => 'reorder'
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    #[Test]
    public function test_user_bisa_mulai_sesi_baru()
    {
        session(['cart' => [1 => ['name' => 'Nasi Goreng', 'quantity' => 1, 'price' => 25000]],
                 'reservation_id' => 1,
                 'table_id' => 1,
                 'customer_name' => 'John']);

        $response = $this->get(route('customer.new-session'));

        $response->assertRedirect(route('select.table'));
        $this->assertFalse(session()->has('cart'));
        $this->assertFalse(session()->has('reservation_id'));
        $this->assertFalse(session()->has('table_id'));
        $this->assertFalse(session()->has('customer_name'));
    }

    #[Test]
    public function test_user_tidak_bisa_akses_menu_tanpa_pilih_meja()
    {
        $response = $this->get(route('user.menu'));
        $response->assertRedirect(route('select.table'));
    }

    #[Test]
    public function test_user_bisa_lihat_menu_setelah_pilih_meja()
    {
        $table = Table::create(['table_number' => 'L1', 'capacity' => 4, 'status' => 'available']);

        Menu::create([
            'name' => 'Nasi Goreng',
            'price' => 25000
        ]);

        session(['table_id' => $table->id]);

        $response = $this->get(route('user.menu'));
        $response->assertStatus(200);
        $response->assertViewHas('table', $table);
        $response->assertViewHas('menus');
        $response->assertViewHas('totalHarga', 0);
    }

    #[Test]
    public function test_user_bisa_lihat_halaman_order_detail()
    {
        $table = Table::create(['table_number' => 'L1', 'capacity' => 4, 'status' => 'occupied']);
        $menu = Menu::create([
            'name' => 'Nasi Goreng',
            'price' => 25000
        ]);

        session(['table_id' => $table->id, 'cart' => [
            $menu->id => ['name' => 'Nasi Goreng', 'quantity' => 2, 'price' => 25000]
        ]]);

        $response = $this->get(route('order.detail'));
        $response->assertStatus(200);
        $response->assertViewHas('cart');
        $response->assertViewHas('table_number');
    }

    #[Test]
    public function test_user_bisa_lihat_halaman_sukses()
    {
        $table = Table::create(['table_number' => 'L1', 'capacity' => 4, 'status' => 'occupied']);
        $reservation = Reservation::create([
            'customer_name' => 'John Doe',
            'table_id' => $table->id,
            'status' => 'paid',
            'phone' => '081234567890',
            'total' => 50000
        ]);

        $response = $this->get(route('payment.success', $reservation->id));
        $response->assertStatus(200);
    }

    #[Test]
    public function test_user_bisa_tambah_menu_ke_keranjang()
    {
        $table = Table::create(['table_number' => 'L1', 'capacity' => 4, 'status' => 'occupied']);
        $menu = Menu::create([
            'name' => 'Nasi Goreng',
            'price' => 25000,
            'stock' => 50
        ]);

        session(['table_id' => $table->id]);

        $response = $this->post('/cart/add', [
            'id' => $menu->id,
            'quantity' => 2
        ]);

        $response->assertStatus(200);
    }

    #[Test]
    public function test_user_bisa_checkout_dengan_data_lengkap()
    {
        $table = Table::create(['table_number' => 'L1', 'capacity' => 4, 'status' => 'available']);
        $menu = Menu::create([
            'name' => 'Nasi Goreng',
            'price' => 25000,
            'stock' => 50
        ]);

        session([
            'table_id' => $table->id,
            'cart' => [
                $menu->id => ['name' => 'Nasi Goreng', 'quantity' => 2, 'price' => 25000]
            ]
        ]);

        $response = $this->post('/order/checkout', [
            'name' => 'John Doe',
            'phone' => '081234567890',
            'total' => 50000
        ]);

        $this->assertDatabaseHas('reservations', [
            'customer_name' => 'John Doe',
            'phone' => '081234567890',
            'table_id' => $table->id
        ]);
    }
}
