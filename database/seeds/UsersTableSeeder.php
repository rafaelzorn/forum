<?php

use Illuminate\Database\Seeder;
use App\Forum\User\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (empty(User::find(1))) {
            User::create([
                'id'       => 1,
                'name'     => 'ADMIN',
                'email'    => 'admin@admin.com.br',
                'password' => bcrypt(123456),
                'is_admin' => 1,
            ]);
        }
    }
}
