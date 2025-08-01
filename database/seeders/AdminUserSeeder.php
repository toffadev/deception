<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer un utilisateur admin par défaut
        User::firstOrCreate(
            ['email' => 'admin@solidaritecoeurbrise.org'],
            [
                'role' => 'admin',
                'pseudo' => 'Admin Principal',
                'email' => 'admin@solidaritecoeurbrise.org',
                'password' => Hash::make('AdminSCB2024!'),
                'birth_date' => now()->subYears(30),
                'status' => 'active',
                'charter_accepted_at' => now(),
                'email_verified_at' => now(),
                'auth_provider' => 'local',
            ]
        );

        // Créer un second utilisateur admin de secours
        User::firstOrCreate(
            ['email' => 'superadmin@solidaritecoeurbrise.org'],
            [
                'role' => 'admin',
                'pseudo' => 'Super Admin',
                'email' => 'superadmin@solidaritecoeurbrise.org',
                'password' => Hash::make('SuperAdminSCB2024!'),
                'birth_date' => now()->subYears(35),
                'status' => 'active',
                'charter_accepted_at' => now(),
                'email_verified_at' => now(),
                'auth_provider' => 'local',
            ]
        );

        // Créer un troisième utilisateur admin personnalisé
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'role' => 'admin',
                'pseudo' => 'Admin Toffadev',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('toffadev'),
                'birth_date' => now()->subYears(28),
                'status' => 'active',
                'charter_accepted_at' => now(),
                'email_verified_at' => now(),
                'auth_provider' => 'local',
            ]
        );


        // Créer quelques utilisateurs clients de test
        User::firstOrCreate(
            ['email' => 'client.test@exemple.com'],
            [
                'role' => 'client',
                'pseudo' => 'Client Test',
                'email' => 'client.test@exemple.com',
                'password' => Hash::make('ClientTest2024!'),
                'birth_date' => now()->subYears(25),
                'status' => 'active',
                'charter_accepted_at' => now(),
                'email_verified_at' => now(),
                'auth_provider' => 'local',
            ]
        );

        User::firstOrCreate(
            ['email' => 'devincetoffa99@gmail.com'],
            [
                'role' => 'client',
                'pseudo' => 'ToffaDev',
                'email' => 'devincetoffa99@gmail.com',
                'password' => Hash::make('toffadev!'),
                'birth_date' => now()->subYears(25),
                'status' => 'active',
                'charter_accepted_at' => now(),
                'email_verified_at' => now(),
                'auth_provider' => 'local',
            ]
        );

        $this->command->info('Utilisateurs par défaut créés avec succès !');
        $this->command->info('Admin: admin@solidaritecoeurbrise.org / AdminSCB2024!');
        $this->command->info('Super Admin: superadmin@solidaritecoeurbrise.org / SuperAdminSCB2024!');
        $this->command->info('Client Test: client.test@exemple.com / ClientTest2024!');
    }
}
