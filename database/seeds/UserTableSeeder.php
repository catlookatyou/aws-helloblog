<?php

use Illuminate\Database\Seeder;
use App\User as UserEloquent;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    DB::table('users')->insert([
        [
		    'name'=>'admin',
		    'email'=>'cat@mail.com',
		    'password'=>bcrypt('12345678'),
		    'type'=>1
        ],
        [
		    'name'=>'dog',
		    'email'=>'dog@mail.com',
		    'password'=>bcrypt('12345678'),
		    'type'=>0
        ],
        [
		    'name'=>'steve',
		    'email'=>'steve@mail.com',
		    'password'=>bcrypt('12345678'),
		    'type'=>0
        ]
    ]);
    }
}
