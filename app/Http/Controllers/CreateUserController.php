<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateUserController extends Controller
{
    public function createUser()
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        return 'User berhasil dibuat! Email: admin@example.com, Password: password';
    }
}
