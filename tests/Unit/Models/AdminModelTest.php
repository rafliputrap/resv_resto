<?php

namespace Tests\Unit\Models;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Support\Facades\Hash;

class AdminModelTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function test_admin_bisa_dibuat()
    {
        $admin = Admin::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
        ]);

        $this->assertDatabaseHas('admins', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);
    }

    #[Test]
    public function test_admin_punya_attributes()
    {
        $admin = Admin::create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => Hash::make('secret'),
        ]);

        $this->assertEquals('Jane Doe', $admin->name);
        $this->assertEquals('jane@example.com', $admin->email);
    }

    #[Test]
    public function test_admin_fillable_attributes()
    {
        $fillable = (new Admin())->getFillable();
        
        $this->assertContains('name', $fillable);
        $this->assertContains('email', $fillable);
        $this->assertContains('password', $fillable);
    }

    #[Test]
    public function test_admin_password_tersembunyi()
    {
        $admin = Admin::create([
            'name' => 'Test',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        $hidden = $admin->getHidden();
        $this->assertContains('password', $hidden);
    }
}
