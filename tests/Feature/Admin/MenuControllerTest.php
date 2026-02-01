<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\Admin;
use App\Models\Menu;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Support\Facades\Hash;

class MenuControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
        ]);
    }

    #[Test]
    public function test_admin_bisa_lihat_daftar_menu()
    {
        Menu::create([
            'name' => 'Nasi Goreng',
            'price' => 25000
        ]);

        $response = $this->actingAs($this->admin, 'web')
            ->get(route('admin.menus.index'));

        $response->assertStatus(200);
        $response->assertViewHas('menus');
    }

    #[Test]
    public function test_admin_bisa_lihat_form_tambah_menu()
    {
        $response = $this->actingAs($this->admin, 'web')
            ->get(route('admin.menus.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.create');
    }

    #[Test]
    public function test_admin_bisa_lihat_form_edit_menu()
    {
        $menu = Menu::create([
            'name' => 'Nasi Goreng',
            'price' => 25000
        ]);

        $response = $this->actingAs($this->admin, 'web')
            ->get(route('admin.menus.edit', $menu));

        $response->assertStatus(200);
        $response->assertViewHas('menu', $menu);
    }

    #[Test]
    public function test_admin_bisa_hapus_menu()
    {
        $menu = Menu::create([
            'name' => 'Nasi Goreng',
            'price' => 25000
        ]);

        $response = $this->actingAs($this->admin, 'web')
            ->delete(route('admin.menus.destroy', $menu));

        $response->assertRedirect('/');
        $this->assertDatabaseMissing('menus', ['id' => $menu->id]);
    }

    #[Test]
    public function test_admin_tidak_bisa_lihat_menu_jika_belum_login()
    {
        $response = $this->get(route('admin.menus.index'));
        $response->assertStatus(200);
    }
}
