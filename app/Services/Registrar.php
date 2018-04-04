<?php
/**
 * Created by PhpStorm.
 * User: aquispe
 * Date: 19/02/2018
 * Time: 12:02
 */

namespace App\Services;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Auth as RegistrarContract;

class Registrar implements RegistrarContract
{

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    public function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'database' => $data['name'] . '.sqlite',
            'password' => bcrypt($data['password']),
        ]);
        // Create the new user sqlite database
        Storage::put($user->database, '');
        // Close any connection made with tenantdb
        DB::disconnect('tenantdb');
        // Set the tenant connection to the users own database
        Config::set('database.connections.tenantdb.database', storage_path().'/'.$user->database);
        // Run migrations for the new db
        Artisan::call('migrate', ['--database' => 'tenantdb', '--path' => 'database/migrations/tenant']);
        return $user;
    }

}