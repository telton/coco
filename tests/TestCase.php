<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();

        $test = $this;

        TestResponse::macro('followRedirects', function ($testCase = null) use ($test) {
            $response = $this;
            $testCase = $testCase ?: $test;

            while ($response->isRedirect()) {
                $response = $testCase->get($response->headers->get('Location'));
            }

            return $response;
        });
    }

    public function createAdminUser()
    {
        // Create the user and the admin role.
        $adminRole = factory(Role::class)->create(['name' => 'admin']);
        $user = factory(User::class)->create([
            'name'           => 'Mark Allison',
            'email'          => 'markalli@umflint.edu',
            'password'       => bcrypt('password'),
            'remember_token' => str_random(60),
            'role_id'        => $adminRole->id,
        ]);

        return $user;
    }

    public function createInstructorUser()
    {
        // Create the user and the instructor role.
        $instructorRole = factory(Role::class)->create(['name' => 'instructor']);
        $user = factory(User::class)->create([
            'name'           => 'Murali Mani',
            'email'          => 'mmani@umflint.edu',
            'password'       => bcrypt('password'),
            'remember_token' => str_random(60),
            'role_id'        => $instructorRole->id,
        ]);

        return $user;
    }

    public function createStudentUser()
    {
        // Create the user and the student role.
        $studentRole = factory(Role::class)->create(['name' => 'student']);
        $user = factory(User::class)->create([
            'name'           => 'Tyler Elton',
            'email'          => 'telton@umflint.edu',
            'password'       => bcrypt('password'),
            'remember_token' => str_random(60),
            'role_id'        => $studentRole->id,
        ]);

        return $user;
    }

    public function createGraderUser()
    {
        // Create the user and the grader role.
        $graderRole = factory(Role::class)->create(['name' => 'grader']);
        $user = factory(User::class)->create([
            'name'           => 'Grader Bob',
            'email'          => 'gradbob@umflint.edu',
            'password'       => bcrypt('password'),
            'remember_token' => str_random(60),
            'role_id'        => $graderRole->id,
        ]);

        return $user;
    }

    public function createTutorUser()
    {
        // Create the user and the tutor role.
        $tutorRole = factory(Role::class)->create(['name' => 'tutor']);
        $user = factory(User::class)->create([
            'name'           => 'Tutor Joe',
            'email'          => 'tutjoe@umflint.edu',
            'password'       => bcrypt('password'),
            'remember_token' => str_random(60),
            'role_id'        => $tutorRole->id,
        ]);

        return $user;
    }
}
