<?php

namespace Tests\Unit\Models;

use App\Models\ReservationDetail;
use App\Models\Reservation;
use App\Models\Menu;
use App\Models\Table;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ReservationDetailModelTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function test_reservation_detail_bisa_dibuat()
    {
        $table = Table::create(['table_number' => 'A1', 'capacity' => 4, 'status' => 'occupied']);
        $menu = Menu::create(['name' => 'Nasi Goreng', 'price' => 25000]);
        $reservation = Reservation::create([
            'order_number' => 'ORD-001',
            'table_id' => $table->id,
            'customer_name' => 'John',
            'phone' => '081234567890',
            'total' => 50000,
            'status' => 'paid',
        ]);

        $detail = ReservationDetail::create([
            'reservation_id' => $reservation->id,
            'menu_id' => $menu->id,
            'quantity' => 2,
            'price' => 25000,
        ]);

        $this->assertDatabaseHas('reservation_details', [
            'quantity' => 2,
            'price' => 25000,
        ]);
    }

    #[Test]
    public function test_reservation_detail_attributes()
    {
        $table = Table::create(['table_number' => 'B1', 'capacity' => 4, 'status' => 'occupied']);
        $menu = Menu::create(['name' => 'Soto Ayam', 'price' => 20000]);
        $reservation = Reservation::create([
            'order_number' => 'ORD-002',
            'table_id' => $table->id,
            'customer_name' => 'Jane',
            'phone' => '089876543210',
            'total' => 40000,
            'status' => 'paid',
        ]);

        $detail = ReservationDetail::create([
            'reservation_id' => $reservation->id,
            'menu_id' => $menu->id,
            'quantity' => 2,
            'price' => 20000,
        ]);

        $this->assertEquals(2, $detail->quantity);
        $this->assertEquals(20000, $detail->price);
    }

    #[Test]
    public function test_reservation_detail_belongs_to_reservation()
    {
        $table = Table::create(['table_number' => 'C1', 'capacity' => 4, 'status' => 'occupied']);
        $menu = Menu::create(['name' => 'Menu Test', 'price' => 15000]);
        $reservation = Reservation::create([
            'order_number' => 'ORD-003',
            'table_id' => $table->id,
            'customer_name' => 'Test',
            'phone' => '085555555555',
            'total' => 30000,
            'status' => 'paid',
        ]);

        $detail = ReservationDetail::create([
            'reservation_id' => $reservation->id,
            'menu_id' => $menu->id,
            'quantity' => 2,
            'price' => 15000,
        ]);

        $this->assertEquals($reservation->id, $detail->reservation_id);
    }

    #[Test]
    public function test_reservation_detail_belongs_to_menu()
    {
        $table = Table::create(['table_number' => 'D1', 'capacity' => 4, 'status' => 'occupied']);
        $menu = Menu::create(['name' => 'Ayam Goreng', 'price' => 30000]);
        $reservation = Reservation::create([
            'order_number' => 'ORD-004',
            'table_id' => $table->id,
            'customer_name' => 'Test2',
            'phone' => '084444444444',
            'total' => 60000,
            'status' => 'paid',
        ]);

        $detail = ReservationDetail::create([
            'reservation_id' => $reservation->id,
            'menu_id' => $menu->id,
            'quantity' => 2,
            'price' => 30000,
        ]);

        $this->assertEquals($menu->id, $detail->menu->id);
    }

    #[Test]
    public function test_reservation_detail_fillable()
    {
        $fillable = (new ReservationDetail())->getFillable();
        
        $this->assertContains('reservation_id', $fillable);
        $this->assertContains('menu_id', $fillable);
        $this->assertContains('quantity', $fillable);
        $this->assertContains('price', $fillable);
    }

    #[Test]
    public function test_reservation_detail_get_subtotal()
    {
        $table = Table::create(['table_number' => 'E1', 'capacity' => 4, 'status' => 'occupied']);
        $menu = Menu::create(['name' => 'Test Menu', 'price' => 25000]);
        $reservation = Reservation::create([
            'order_number' => 'ORD-005',
            'table_id' => $table->id,
            'customer_name' => 'Test',
            'phone' => '081234567890',
            'total' => 75000,
            'status' => 'paid',
        ]);

        $detail = ReservationDetail::create([
            'reservation_id' => $reservation->id,
            'menu_id' => $menu->id,
            'quantity' => 3,
            'price' => 25000,
        ]);

        $this->assertEquals(75000, $detail->getSubtotal());
    }

    #[Test]
    public function test_reservation_detail_get_subtotal_formatted()
    {
        $table = Table::create(['table_number' => 'F1', 'capacity' => 4, 'status' => 'occupied']);
        $menu = Menu::create(['name' => 'Test Menu', 'price' => 50000]);
        $reservation = Reservation::create([
            'order_number' => 'ORD-006',
            'table_id' => $table->id,
            'customer_name' => 'Test',
            'phone' => '081234567890',
            'total' => 100000,
            'status' => 'paid',
        ]);

        $detail = ReservationDetail::create([
            'reservation_id' => $reservation->id,
            'menu_id' => $menu->id,
            'quantity' => 2,
            'price' => 50000,
        ]);

        $this->assertEquals('Rp 100.000', $detail->getSubtotalFormatted());
    }

    #[Test]
    public function test_reservation_detail_get_quantity_display()
    {
        $table = Table::create(['table_number' => 'G1', 'capacity' => 4, 'status' => 'occupied']);
        $menu = Menu::create(['name' => 'Test Menu', 'price' => 15000]);
        $reservation = Reservation::create([
            'order_number' => 'ORD-007',
            'table_id' => $table->id,
            'customer_name' => 'Test',
            'phone' => '081234567890',
            'total' => 30000,
            'status' => 'paid',
        ]);

        $detail = ReservationDetail::create([
            'reservation_id' => $reservation->id,
            'menu_id' => $menu->id,
            'quantity' => 2,
            'price' => 15000,
        ]);

        $this->assertEquals('2 x', $detail->getQuantityDisplay());
    }

    #[Test]
    public function test_reservation_detail_get_price_formatted()
    {
        $table = Table::create(['table_number' => 'H1', 'capacity' => 4, 'status' => 'occupied']);
        $menu = Menu::create(['name' => 'Test Menu', 'price' => 50000]);
        $reservation = Reservation::create([
            'order_number' => 'ORD-008',
            'table_id' => $table->id,
            'customer_name' => 'Test',
            'phone' => '081234567890',
            'total' => 50000,
            'status' => 'paid',
        ]);

        $detail = ReservationDetail::create([
            'reservation_id' => $reservation->id,
            'menu_id' => $menu->id,
            'quantity' => 1,
            'price' => 50000,
        ]);

        $this->assertEquals('Rp 50.000', $detail->getPriceFormatted());
    }
}

