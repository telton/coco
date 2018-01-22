<?php

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     */
    public function run()
    {
        $role = Role::firstOrNew(['name' => 'admin']);
        if (!$role->exists) {
            $role->fill([
                    'display_name' => 'Administrator',
                ])->save();
        }

        $role = Role::firstOrNew(['name' => 'instructor']);
        if (!$role->exists) {
            $role->fill([
                    'display_name' => 'Instructor for course(s)',
                ])->save();
        }

        $role = Role::firstOrNew(['name' => 'grader']);
        if (!$role->exists) {
            $role->fill([
                    'display_name' => 'Grader for course(s)',
                ])->save();
        }

        $role = Role::firstOrNew(['name' => 'tutor']);
        if (!$role->exists) {
            $role->fill([
                    'display_name' => 'Tutor for course(s)',
                ])->save();
        }

        $role = Role::firstOrNew(['name' => 'student']);
        if (!$role->exists) {
            $role->fill([
                    'display_name' => 'Student User',
                ])->save();
        }

        // $role = Role::firstOrNew(['name' => 'user']);
        // if (!$role->exists) {
        //     $role->fill([
        //             'display_name' => 'Normal User',
        //         ])->save();
        // }
    }
}
