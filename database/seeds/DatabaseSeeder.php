<?php

use App\User;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//         $this->call(UsersTableSeeder::class);
//        $faker = (new Faker());
//        \DB::table('users')->insert([
//            'username' => "Alex Christian",
//            'email' => "aquisper@sapia.com.pe",
//            'unpassword' => "sapia.2018",
//            'password' => app('hash')->make('sapia.2018'),
//            'database' => 'entel'
//        ]);

        \DB::table('users')->insert([
            'username' => "Deysi Carolina",
            'email' => "dquispe@sapia.com.pe",
            'unpassword' => "sapia.2018",
            'password' => app('hash')->make('sapia.2018'),
            'database' => 'interbank'
        ]);

    }
}
