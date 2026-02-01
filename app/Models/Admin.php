<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class Admin extends Authenticatable
{
    protected $table = 'admins'; 

    protected $fillable = ['name','email','password'];
    protected $hidden = ['password'];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function getFullName()
    {
        return strtoupper($this->name);
    }

    public function getInitials()
    {
        $names = explode(' ', $this->name);
        return substr($names[0], 0, 1) . (isset($names[1]) ? substr($names[1], 0, 1) : '');
    }
}