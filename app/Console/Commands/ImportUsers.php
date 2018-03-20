<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Watson\Validating\ValidationException;
use App\Models\Courses\Course;
use App\Models\User;
use TCG\Voyager\Models\Role;

class ImportUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:users {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        if (!file_exists($this->argument('file'))) {
            $this->error('Passed file does not exist.');
            return;
        }
        $file = fopen($this->argument('file'), 'r');

        while (($data = fgetcsv($file)) !== false) {
            list($name, $email, $password, $role_name, ) = $data;

            try {
                //$slug = strtolower($email . $course_number . '-' . $section);

                // Try to find the instructor.
                $user = User::where('email', $email)->first();
                $role_id = Role::where('name', $role_name)->first();

                // If the email was not found create the user.
                if (!$user) {
                    $user = User::create([
                        'name'           => $name,
                        'email'          => $email,
                        'password'       => bcrypt($password),
                        'remember_token' => str_random(60),
                        'role_id'        => $role_id->id,
                    ]);
                }
                //If email is found don't create user
                else{
                    $this->line("User: {$name} Email: {$email} already exists");
                    continue;
                }
            } catch (ValidationException $e) {
                $this->line("User: {$name} Email: {$email} could not be imported");
                continue;
            }
        }

        fclose($file);
    }
}
