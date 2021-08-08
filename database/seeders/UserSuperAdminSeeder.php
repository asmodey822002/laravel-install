<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'email' => 'admin@mail.com',
                'password' => bcrypt('secret'),
                'name' => 'SuperAdmin',
            ],
        ];

        foreach($users as $key=>$user)
        {
            $user =  User::firstOrCreate(['email' => $user['email']], $user);
        }
    }
}
