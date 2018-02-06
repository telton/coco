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
            $adminRole = Role::where('name', 'admin')->firstOrFail();
            $instructorRole = Role::where('name', 'instructor')->firstOrFail();

            // Admins.
            User::create([
                'name'           => 'Tyler Elton',
                'email'          => 'telton@umflint.edu',
                'password'       => bcrypt('password'),
                'remember_token' => str_random(60),
                'role_id'        => $adminRole->id,
            ]);
            User::create([
                'name'           => 'Jason Moehlman',
                'email'          => 'jmoehlman@umflint.edu',
                'password'       => bcrypt('password'),
                'remember_token' => str_random(60),
                'role_id'        => $adminRole->id,
            ]);
            User::create([
                'name'           => 'Brandon Krug',
                'email'          => 'brkrug@umflint.edu',
                'password'       => bcrypt('password'),
                'remember_token' => str_random(60),
                'role_id'        => $adminRole->id,
            ]);
            User::create([
                'name'           => 'Zach Nelson',
                'email'          => 'zacharyn@umflint.edu',
                'password'       => bcrypt('password'),
                'remember_token' => str_random(60),
                'role_id'        => $adminRole->id,
            ]);
            User::create([
                'name'           => 'Gary Landrum',
                'email'          => 'glandrum@umflint.edu',
                'password'       => bcrypt('password'),
                'remember_token' => str_random(60),
                'role_id'        => $adminRole->id,
            ]);
            User::create([
                'name'           => 'Mark Allison',
                'email'          => 'markalli@umflint.edu',
                'password'       => bcrypt('password'),
                'remember_token' => str_random(60),
                'role_id'        => $adminRole->id,
            ]);
            // Instructors.
            User::create([
                'name'           => 'Murali Mani',
                'email'          => 'mmani@umflint.edu',
                'password'       => bcrypt('password'),
                'remember_token' => str_random(60),
                'role_id'        => $instructorRole->id,
            ]);
            User::create([
                'name'           => 'Jeffery Livermore',
                'email'          => 'jefflive@umflint.edu',
                'password'       => bcrypt('password'),
                'remember_token' => str_random(60),
                'role_id'        => $instructorRole->id,
            ]);
            User::create([
                'name'           => 'Vijayaditya Ayyagari',
                'email'          => 'vayyagar@umflint.edu',
                'password'       => bcrypt('password'),
                'remember_token' => str_random(60),
                'role_id'        => $instructorRole->id,
            ]);
            User::create([
                'name'           => 'Lixing Han',
                'email'          => 'lxhan@umflint.edu',
                'password'       => bcrypt('password'),
                'remember_token' => str_random(60),
                'role_id'        => $instructorRole->id,
            ]);
            User::create([
                'name'           => 'Ricardo Alfaro',
                'email'          => 'ralfaro@umflint.edu',
                'password'       => bcrypt('password'),
                'remember_token' => str_random(60),
                'role_id'        => $instructorRole->id,
            ]);
            User::create([
                'name'           => 'Alla Dubrovich',
                'email'          => 'dubrovic@umflint.edu',
                'password'       => bcrypt('password'),
                'remember_token' => str_random(60),
                'role_id'        => $instructorRole->id,
            ]);
        }
    }
}
