<?php

namespace Tests\Unit\Models;

use App\Models\Reservation;
use App\Models\Table;
use App\Models\Menu;
use App\Models\ReservationDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ReservationModelTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function test_reservation_bisa_dibuat()
    {
        $table = Table::create(['table_number' => 'A1', 'capacity' => 4, 'status' => 'occupied']);
        
        $reservation = Reservation::create([
            'order_number' => 'ORD-001',
            'table_id' => $table->id,
            'customer_name' => 'John Doe',
            'phone' => '081234567890',
            'total' => 100000,
            'status' => 'paid',
        ]);

        $this->assertDatabaseHas('reservations', [
            'customer_name' => 'John Doe',
            'phone' => '081234567890',
        ]);
    }

    #[Test]
    public function test_reservation_attributes()
    {
        $table = Table::create(['table_number' => 'A1', 'capacity' => 4, 'status' => 'occupied']);
        
        $reservation = Reservation::create([
            'order_number' => 'ORD-002',
            'table_id' => $table->id,
            'customer_name' => 'Jane Doe',
            'phone' => '089876543210',
            'total' => 150000,
            'status' => 'confirmed',
        ]);

        $this->assertEquals('Jane Doe', $reservation->customer_name);
        $this->assertEquals('089876543210', $reservation->phone);
        $this->assertEquals(150000, $reservation->total);
        $this->assertEquals('confirmed', $reservation->status);
    }

    #[Test]
    public function test_reservation_fillable()
    {
        $fillable = (new Reservation())->getFillable();
        
        $this->assertContains('customer_name', $fillable);
        $this->assertContains('phone', $fillable);
        $this->assertContains('total', $fillable);
        $this->assertContains('status', $fillable);
        $this->assertContains('table_id', $fillable);
    }

    #[Test]
    public function test_reservation_belongs_to_table()
    {
        $table = Table::create(['table_number' => 'B1', 'capacity' => 4, 'status' => 'occupied']);
        
        $reservation = Reservation::create([
            'order_number' => 'ORD-003',
            'table_id' => $table->id,
            'customer_name' => 'Test User',
            'phone' => '085555555555',
            'total' => 75000,
            'status' => 'paid',
        ]);

        $this->assertInstanceOf(Table::class, $reservation->table);
        $this->assertEquals($table->id, $reservation->table->id);
    }

    #[Test]
    public function test_reservation_multiple_creation()
    {
        $table = Table::create(['table_number' => 'C1', 'capacity' => 4, 'status' => 'occupied']);
        
        Reservation::create([
            'order_number' => 'ORD-004',
            'table_id' => $table->id,
            'customer_name' => 'Test User',
            'phone' => '085555555555',
            'total' => 45000,
            'status' => 'paid',
        ]);

        Reservation::create([
            'order_number' => 'ORD-005',
            'table_id' => $table->id,
            'customer_name' => 'Test User 2',
            'phone' => '085555555556',
            'total' => 50000,
            'status' => 'paid',
        ]);

        $this->assertEquals(2, Reservation::count());
    }

    #[Test]
    public function test_reservation_get_status_display()
    {
        $table = Table::create(['table_number' => 'D1', 'capacity' => 4, 'status' => 'occupied']);
        
        $statuses = ['paid', 'pending_payment', 'confirmed', 'rejected', 'completed'];

        foreach ($statuses as $status) {
            Reservation::create([
                'order_number' => 'ORD-' . $status,
                'table_id' => $table->id,
                'customer_name' => 'Test',
                'phone' => '081234567890',
                'total' => 50000,
                'status' => $status,
            ]);
        }

        $this->assertEquals(5, Reservation::count());
    }

    #[Test]
    public function test_reservation_check_status_display()
    {
        $table = Table::create(['table_number' => 'E1', 'capacity' => 4, 'status' => 'occupied']);

        $res_pending = Reservation::create([
            'order_number' => 'ORD-006',
            'table_id' => $table->id,
            'customer_name' => 'Test',
            'phone' => '081234567890',
            'total' => 50000,
            'status' => 'pending',
        ]);

        $res_confirmed = Reservation::create([
            'order_number' => 'ORD-007',
            'table_id' => $table->id,
            'customer_name' => 'Test',
            'phone' => '081234567890',
            'total' => 50000,
            'status' => 'confirmed',
        ]);

        $res_completed = Reservation::create([
            'order_number' => 'ORD-008',
            'table_id' => $table->id,
            'customer_name' => 'Test',
            'phone' => '081234567890',
            'total' => 50000,
            'status' => 'completed',
        ]);

        $this->assertEquals('Menunggu', $res_pending->getStatusDisplay());
        $this->assertEquals('Dikonfirmasi', $res_confirmed->getStatusDisplay());
        $this->assertEquals('Selesai', $res_completed->getStatusDisplay());
    }

    #[Test]
    public function test_reservation_get_total_formatted()
    {
        $table = Table::create(['table_number' => 'F1', 'capacity' => 4, 'status' => 'occupied']);

        $reservation = Reservation::create([
            'order_number' => 'ORD-009',
            'table_id' => $table->id,
            'customer_name' => 'Test',
            'phone' => '081234567890',
            'total' => 123456,
            'status' => 'pending',
        ]);

        $this->assertEquals('Rp 123.456', $reservation->getTotalFormatted());
    }

    #[Test]
    public function test_reservation_is_completed()
    {
        $table = Table::create(['table_number' => 'G1', 'capacity' => 4, 'status' => 'occupied']);

        $res_completed = Reservation::create([
            'order_number' => 'ORD-010',
            'table_id' => $table->id,
            'customer_name' => 'Test',
            'phone' => '081234567890',
            'total' => 50000,
            'status' => 'completed',
        ]);

        $res_pending = Reservation::create([
            'order_number' => 'ORD-011',
            'table_id' => $table->id,
            'customer_name' => 'Test',
            'phone' => '081234567890',
            'total' => 50000,
            'status' => 'pending',
        ]);

        $this->assertTrue($res_completed->isCompleted());
        $this->assertFalse($res_pending->isCompleted());
    }

    #[Test]
    public function test_reservation_get_formatted_reservation_time()
    {
        $table = Table::create(['table_number' => 'H1', 'capacity' => 4, 'status' => 'occupied']);

        $reservation = Reservation::create([
            'order_number' => 'ORD-012',
            'table_id' => $table->id,
            'customer_name' => 'Test',
            'phone' => '081234567890',
            'total' => 50000,
            'status' => 'pending',
            'reservation_time' => '2025-12-31 19:30:00',
        ]);

        $this->assertEquals('31/12/2025 19:30', $reservation->getFormattedReservationTime());
    }
}

