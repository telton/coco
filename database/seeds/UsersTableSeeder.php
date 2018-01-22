<?php

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Role;
use TCG\Voyager\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        if (User::count() == 0) {
            $role = Role::where('name', 'admin')->firstOrFail();

            // User::create([
            //     'name'           => 'Admin',
            //     'email'          => 'admin@admin.com',
            //     'password'       => bcrypt('password'),
            //     'remember_token' => str_random(60),
            //     'role_id'        => $role->id,
            // ]);
            User::create([
                'name'           => 'Tyler Elton',
                'email'          => 'telton@umflint.edu',
                'password'       => bcrypt('password'),
                'remember_token' => str_random(60),
                'role_id'        => $role->id,
            ]);
            User::create([
                'name'           => 'Jason Moehlman',
                'email'          => 'jmoehlman@umflint.edu',
                'password'       => bcrypt('password'),
                'remember_token' => str_random(60),
                'role_id'        => $role->id,
            ]);
            User::create([
                'name'           => 'Brandon Krug',
                'email'          => 'brkrug@umflint.edu',
                'password'       => bcrypt('password'),
                'remember_token' => str_random(60),
                'role_id'        => $role->id,
            ]);
            User::create([
                'name'           => 'Zach Nelson',
                'email'          => 'zacharyn@umflint.edu',
                'password'       => bcrypt('password'),
                'remember_token' => str_random(60),
                'role_id'        => $role->id,
            ]);
            User::create([
                'name'           => 'Gary Landrum',
                'email'          => 'glandrum@umflint.edu',
                'password'       => bcrypt('password'),
                'remember_token' => str_random(60),
                'role_id'        => $role->id,
            ]);
            User::create([
                'name'           => 'Mark Allison',
                'email'          => 'markalli@umflint.edu',
                'password'       => bcrypt('password'),
                'remember_token' => str_random(60),
                'role_id'        => $role->id,
            ]);
        }
    }
}
