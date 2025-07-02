<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'role' => 'user',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        User::create([
            'name' => 'Agent 1',
            'email' => 'agent1@example.com',
            'role' => 'support_agent',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        User::create([
            'name' => 'Agent 2',
            'email' => 'agent2@example.com',
            'role' => 'support_agent',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
    }
}
