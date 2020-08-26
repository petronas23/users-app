<?php

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('auth_types')->insert([
            [
                'name' => 'Vk',
                'type' => 'vkontakte',
                'client_id' => '123',
                'token' => '456'
            ],
            [
                'name' => 'Github',
                'type' => 'github',
                'client_id' => '123',
                'token' => '456' 
            ]
        ]);
    }
}

