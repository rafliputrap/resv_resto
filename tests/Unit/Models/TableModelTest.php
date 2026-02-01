<?php

namespace Tests\Unit\Models;

use App\Models\Table;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class TableModelTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function test_table_bisa_dibuat()
    {
        $table = Table::create([
            'table_number' => 'A1',
            'capacity' => 4,
            'status' => 'available',
        ]);

        $this->assertDatabaseHas('tables', [
            'table_number' => 'A1',
            'capacity' => 4,
        ]);
    }

    #[Test]
    public function test_table_attributes()
    {
        $table = Table::create([
            'table_number' => 'B2',
            'capacity' => 6,
            'status' => 'occupied',
        ]);

        $this->assertEquals('B2', $table->table_number);
        $this->assertEquals(6, $table->capacity);
        $this->assertEquals('occupied', $table->status);
    }

    #[Test]
    public function test_table_fillable()
    {
        $fillable = (new Table())->getFillable();
        
        $this->assertContains('table_number', $fillable);
        $this->assertContains('capacity', $fillable);
        $this->assertContains('status', $fillable);
    }

    #[Test]
    public function test_table_default_status()
    {
        $table = Table::create([
            'table_number' => 'C3',
            'capacity' => 2,
            'status' => 'available',
        ]);

        $this->assertEquals('available', $table->status);
    }

    #[Test]
    public function test_multiple_tables_bisa_dibuat()
    {
        Table::create(['table_number' => 'D1', 'capacity' => 4, 'status' => 'available']);
        Table::create(['table_number' => 'D2', 'capacity' => 4, 'status' => 'available']);

        $this->assertEquals(2, Table::count());
    }

    #[Test]
    public function test_table_increment_decrement()
    {
        $table = Table::create([
            'table_number' => 'E1',
            'capacity' => 4,
            'status' => 'available',
        ]);

        $table->increment('capacity');
        $this->assertEquals(5, $table->refresh()->capacity);
    }

    #[Test]
    public function test_table_get_status_label()
    {
        $table_available = Table::create(['table_number' => 'F1', 'capacity' => 4, 'status' => 'available']);
        $table_occupied = Table::create(['table_number' => 'F2', 'capacity' => 4, 'status' => 'occupied']);

        $this->assertEquals('Tersedia', $table_available->getStatusLabel());
        $this->assertEquals('Digunakan', $table_occupied->getStatusLabel());
    }

    #[Test]
    public function test_table_is_available()
    {
        $table_available = Table::create(['table_number' => 'G1', 'capacity' => 4, 'status' => 'available']);
        $table_occupied = Table::create(['table_number' => 'G2', 'capacity' => 4, 'status' => 'occupied']);

        $this->assertTrue($table_available->isAvailable());
        $this->assertFalse($table_occupied->isAvailable());
    }

    #[Test]
    public function test_table_get_display_number()
    {
        $table = Table::create(['table_number' => 'H1', 'capacity' => 4, 'status' => 'available']);
        $this->assertEquals('Meja H1', $table->getDisplayNumber());
    }
}

