<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Watson\Validating\ValidationException;
use App\Models\Courses\Course;
use App\Models\User;

class ImportCourses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:courses {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import courses';

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
        if (!file_exists($this->argument('file'))) {
            $this->error('Passed file does not exist.');
            return;
        }
        $file = fopen($this->argument('file'), 'r');

        while (($data = fgetcsv($file)) !== false) {
            list($subject, $course_number, $section, $crn, $title, $capacity,
                $campus, $credits, $semester, $year, $instructor) = $data;

            try {
                $slug = strtolower($subject . $course_number . '-' . $section);

                // Try to find the instructor.
                $instructor = User::where('email', "{$instructor}@umflint.edu")->first();

                // If the instructor was not found, alert and continue.
                if (!$instructor) {
                    $this->line("CRN: {$crn} Course: {subject} {$course_number} could not be imported because the instructor was not found in the system!");
                    $this->line("Instructor {$instructor} needs to be created first!");
                    continue;
                }

                $course = Course::create([
                    'subject'       => $subject,
                    'course_number' => $course_number,
                    'section'       => $section,
                    'slug'          => $slug,
                    'crn'           => $crn,
                    'title'         => $title,
                    'capacity'      => $capacity,
                    'campus'        => $campus,
                    'credits'       => $credits,
                    'semester'      => $semester,
                    'year'          => $year,
                    'instructor_id' => $instructor->id,
                ]);
            } catch (ValidationException $e) {
                $this->line("CRN: {$crn} Course: {subject} {$course_number} could not be imported!");
                continue;
            }
        }

        fclose($file);
    }
}
