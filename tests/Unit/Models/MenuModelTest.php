<?php

namespace Tests\Unit\Models;

use App\Models\Menu;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class MenuModelTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function test_menu_bisa_dibuat()
    {
        $menu = Menu::create([
            'name' => 'Nasi Goreng',
            'price' => 25000,
            'category' => 'Main Course',
            'stock' => 50,
        ]);

        $this->assertDatabaseHas('menus', [
            'name' => 'Nasi Goreng',
            'price' => 25000,
        ]);
    }

    #[Test]
    public function test_menu_attributes()
    {
        $menu = Menu::create([
            'name' => 'Soto Ayam',
            'price' => 20000,
            'category' => 'Soup',
            'stock' => 30,
        ]);

        $this->assertEquals('Soto Ayam', $menu->name);
        $this->assertEquals(20000, $menu->price);
        $this->assertEquals('Soup', $menu->category);
        $this->assertEquals(30, $menu->stock);
    }

    #[Test]
    public function test_menu_fillable()
    {
        $fillable = (new Menu())->getFillable();
        
        $this->assertContains('name', $fillable);
        $this->assertContains('price', $fillable);
        $this->assertContains('category', $fillable);
        $this->assertContains('stock', $fillable);
    }

    #[Test]
    public function test_menu_casts()
    {
        $menu = Menu::create([
            'name' => 'Test Menu',
            'price' => 15000,
            'category' => 'Drink',
            'stock' => 100,
        ]);

        $this->assertIsInt($menu->price);
    }

    #[Test]
    public function test_menu_table_name()
    {
        $menu = new Menu();
        $this->assertEquals('menus', $menu->getTable());
    }

    #[Test]
    public function test_menu_get_formatted_price()
    {
        $menu = Menu::create([
            'name' => 'Nasi Kuning',
            'price' => 50000,
            'category' => 'Rice',
            'stock' => 10,
        ]);

        $this->assertEquals('Rp 50.000', $menu->getFormattedPrice());
    }

    #[Test]
    public function test_menu_is_available_with_stock()
    {
        $menu = Menu::create([
            'name' => 'Mie Goreng',
            'price' => 20000,
            'category' => 'Noodles',
            'stock' => 5,
        ]);

        $this->assertTrue($menu->isAvailable());
    }

    #[Test]
    public function test_menu_get_stock_status()
    {
        $menu_habis = Menu::create([
            'name' => 'Menu Habis',
            'price' => 15000,
            'category' => 'Test',
            'stock' => 0,
        ]);

        $menu_terbatas = Menu::create([
            'name' => 'Menu Terbatas',
            'price' => 15000,
            'category' => 'Test',
            'stock' => 5,
        ]);

        $menu_tersedia = Menu::create([
            'name' => 'Menu Tersedia',
            'price' => 15000,
            'category' => 'Test',
            'stock' => 20,
        ]);

        $this->assertEquals('Habis', $menu_habis->getStockStatus());
        $this->assertEquals('Terbatas', $menu_terbatas->getStockStatus());
        $this->assertEquals('Tersedia', $menu_tersedia->getStockStatus());
    }
}

