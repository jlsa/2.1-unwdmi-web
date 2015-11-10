<?php

use Illuminate\Database\Seeder;
use Leertaak5\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'email' => 'admin@admin.admin',
            'name' => 'admin',
            'password' => bcrypt('admin'),
            'rights' => 1
        ]);

         User::create([
            'email' => 'user@user.user',
            'name' => 'user',
            'password' => bcrypt('user'),
            'rights' => 0
        ]);

    }
}
