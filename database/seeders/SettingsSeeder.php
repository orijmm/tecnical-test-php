<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::firstOrCreate(['slug' => UserRole::Admin->value], ['name' => 'Administrador']);
        Role::firstOrCreate(['slug' => UserRole::Client->value], ['name' => 'Cliente']);
        Role::firstOrCreate(['slug' => UserRole::Agent->value], ['name' => 'Agente']);

        $admin = User::factory()->create([
            'name' => 'Administrador Demo',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        $admin->roles()->attach(Role::where('slug', UserRole::Admin->value)->first());

        $agent = User::factory()->create([
            'name' => 'Agente Demo',
            'email' => 'agente@example.com',
            'password' => bcrypt('password'),
        ]);
        $agent->roles()->attach(Role::where('slug', UserRole::Agent->value)->first());

        $client = User::factory()->create([
            'name' => 'Cliente Demo',
            'email' => 'cliente@example.com',
            'password' => bcrypt('password'),
        ]);
        $client->roles()->attach(Role::where('slug', UserRole::Client->value)->first());
    }
}
