<?php

namespace App\Http\Controllers\Courses;

use App\Models\Courses\Grade;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Courses\Course;
use App\Models\Courses\Submission;
use App\Models\Courses\Assignment;
use Illuminate\Support\Facades\Auth;

class GradesController extends Controller
{
    /**
     * GradesController constructor.
     *
     * @author Tyler Elton <telton@umflint.edu>
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('courses.grades')
            ->only(['dashboard', 'store', 'destroy']);
    }

    /**
     * Grades dashboard for admins/instructors/graders.
     *
     * @author Tyler Elton <telton@umflint.edu>
     * @param string $slug
     * @returns \Illuminate\Http\Response
     */
    public function dashboard(string $slug)
    {
        $course = Course::where('slug', $slug)->first();

        // If the course was not found, abort with a status of 404.
        if (!$course) {
            abort(404);
        }

        $submissions = [];
        $assignments = [
            'ungraded'  => [],
            'completed' => [],
        ];

        foreach ($course->assignments as $assignment) {
            foreach ($assignment->submissions() as $submission) {
                // If the assignment submission does not have a grade yet,
                // add it to the ungraded index of the $submissions array.
                // If the assignment submission has a grade, but is not approved,
                // add it to the unapproved index of the $submissions array.
                // Otherwise, that means that there is a grade for that submission and it is approved.
                // Add that submission to the completed index of the $submissions array.
                if (!$submission->grade($assignment->id)) {
                    $submissions['ungraded'][] = $submission;

                    if (!in_array($assignment, $assignments)) {
                        $assignments['ungraded'][] = $assignment;
                    }
                } else {
                    $submissions['completed'][] = $submission;

                    if (!in_array($assignment, $assignments)) {
                        $assignments['completed'][] = $assignment;
                    }
                }
            }
        }

        return view('courses.grades.dashboard', [
            'course'      => $course,
            'assignments' => $assignments,
            'submissions' => $submissions,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param string $slug
     * @return \Illuminate\Http\Response
     */
    public function index(string $slug)
    {
        $course = Course::where('slug', $slug)->first();

        // If the course was not found, abort with a status of 404.
        if (!$course) {
            abort(404);
        }

        $this->breadcrumb->addCrumb(strtoupper($course->slug), route('courses.show', $course->slug));
        $this->breadcrumb->addCrumb('Grades', route('courses.grades.index', $course->slug));
        return view('courses.grades.index', [
            'course' => $course,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param string                         $slug
     * @param \App\Models\Courses\Assignment $assignment
     * @param  \Illuminate\Http\Request      $request
     * @return \Illuminate\Http\Response
     */
    public function store(string $slug, Assignment $assignment, Request $request)
    {
        $submission = Submission::where('id', $request->input('submissionId'))->first();
        $grade = 0.00;

        try {
            $grade = (float) $request->input('pointsEarned') / (float) $request->input('totalPoints');
        } catch (\ErrorException $e) {
            $this->flash()->error("An error occured: {$e->getMessage()}");
            return $this->redirect()->route('courses.grades.dashboard', $slug);
        }

        $grade = Grade::create([
            'assignment_id' => $assignment->id,
            'student_id'    => $submission->user->id,
            'grader_id'     => Auth::user()->id,
            'points_earned' => $request->input('pointsEarned'),
            'grade'         => $grade,
            'letter_grade'  => strtoupper($request->input('letterGrade')),
            'comments'      => $request->input('gradeComments'),
        ]);

        $this->flash()->success("The grade for the assignment <strong>{$assignment->name}</strong> has been recorded for student <strong>{$submission->user->name}</strong>!");
        return $this->redirect()->route('courses.grades.dashboard', $slug);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request       $request
     * @param  string                         $slug
     * @param  \App\Models\Courses\Assignment $assignment
     * @param  \App\Models\Courses\Grade      $grade
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, string $slug, Assignment $assignment, Grade $grade)
    {
        // dd($request->input());

        $submission = Submission::where('id', $request->input('submissionId'))->first();

        $grade->update([
            'assignment_id' => $assignment->id,
            'student_id'    => $submission->user->id,
            'grader_id'     => Auth::user()->id,
            'points_earned' => $request->input('pointsEarned'),
            'grade'         => $request->input('grade'),
            'letter_grade'  => strtoupper($request->input('letterGrade')),
            'comments'      => $request->input('gradeEditComments'),
        ]);

        $this->flash()->success("The grade for the assignment <strong>{$assignment->name}</strong> has been updated for student <strong>{$submission->user->name}</strong>!");
        return $this->redirect()->route('courses.grades.dashboard', $slug);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string                          $slug
     * @param  \App\Models\Courses\Assignment $assignment
     * @param  \App\Models\Courses\Grade      $grade
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(string $slug, Assignment $assignment, Grade $grade)
    {
        $student = $grade->student;

        // Attempt to delete the grade.
        if ($grade->delete()) {
            $this->flash()->success("The grade for the assignment <strong>{$assignment->name}</strong> has been deleted for student <strong>{$student->name}</strong>!");
            return $this->redirect()->route('courses.grades.dashboard', $slug);
        } else {
            $this->flash()->warning("The grade for the assignment <strong>{$assignment->name}</strong> was NOT deleted for student <strong>{$student->name}</strong>!");
            return $this->redirect()->route('courses.grades.dashboard', $slug);
        }
    }

    /**
     * Export to CSV for student.
     *
     * @author Tyler Elton <telton@umflint.edu>
     * @param string $slug
     */
    public function studentExport(string $slug)
    {
        $course = Course::where('slug', $slug)->first();

        $csvData = ['Assignment Name,Submitted On,Points Earned,Total Points,Grade,Letter Grade,Graded By'];
        foreach ($course->visibleAssignments as $assignment) {
            if ($assignment->submission() && $assignment->submission()->grade()) {
                $csvData[] = $assignment->name . ',' . $assignment->submission()->created_at->format('m/d/Y h:i A') . ',' . $assignment->submission()->grade()->points_earned . ',' . $assignment->points
                    . ',' . number_format($assignment->submission()->grade()->grade * 100, 2) . '%,' . $assignment->submission()->grade()->letter_grade . ',' . $assignment->submission()->grade()->grader->name;
            }
        }

        // Output headers so that the file is downloaded rather than displayed.
        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename="' . date('m-d-Y') . '_grades.csv"');

        // Don't cache the file.
        header('Pragma: no-cache');
        header('Expires: 0');

        // Create and write to the CSV.
        $file = fopen('php://output', 'w');
        foreach ($csvData as $data) {
            fputcsv($file, explode(',', $data));
        }
        fclose($file);
    }

    /**
     * Export entire assignment's grades to CSV for instructor/grader.
     *
     * @author Tyler Elton <telton@umflint.edu>
     * @param string                         $slug
     * @param \App\Models\Courses\Assignment $assignment
     */
    public function assignmentExport(string $slug, Assignment $assignment)
    {
        $course = Course::where('slug', $slug)->first();

        $csvData = ['Assignment Name,Student Name,Submitted On,Points Earned,Total Points,Grade,Letter Grade,Graded By'];
        foreach ($assignment->submissions() as $submission) {
            if ($submission->grade()) {
                $csvData[] = $assignment->name . ',' . $submission->user->name . ',' . $submission->created_at->format('m/d/Y h:i A') . ',' . $submission->grade()->points_earned . ',' . $assignment->points
                    . ',' . number_format($submission->grade()->grade * 100, 2) . '%,' . $submission->grade()->letter_grade . ',' . $submission->grade()->grader->name;
            }
        }

        // Output headers so that the file is downloaded rather than displayed.
        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename="' . date('m-d-Y') . '_' . $slug . '_' . str_replace(' ', '_', strtolower($assignment->name)) . '_grades.csv"');

        // Don't cache the file.
        header('Pragma: no-cache');
        header('Expires: 0');

        // Create and write to the CSV.
        $file = fopen('php://output', 'w');
        foreach ($csvData as $data) {
            fputcsv($file, explode(',', $data));
        }
        fclose($file);
    }

    /**
     * Export entire courses's grades to CSV for instructor/grader.
     *
     * @author Tyler Elton <telton@umflint.edu>
     * @param string $slug
     */
    public function dashboardExport(string $slug)
    {
        $course = Course::where('slug', $slug)->first();

        $csvData = ['Assignment Name,Student Name,Submitted On,Points Earned,Total Points,Grade,Letter Grade,Graded By'];
        foreach ($course->visibleAssignments as $assignment) {
            foreach ($assignment->submissions() as $submission) {
                if ($submission->grade()) {
                    $csvData[] = $assignment->name . ',' . $submission->user->name . ',' . $submission->created_at->format('m/d/Y h:i A') . ',' . $submission->grade()->points_earned . ',' . $assignment->points
                        . ',' . number_format($submission->grade()->grade * 100, 2) . '%,' . $submission->grade()->letter_grade . ',' . $submission->grade()->grader->name;
                }
            }
        }

        // Output headers so that the file is downloaded rather than displayed.
        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename="' . date('m-d-Y') . '_' . $slug . '_grades.csv"');

        // Don't cache the file.
        header('Pragma: no-cache');
        header('Expires: 0');

        // Create and write to the CSV.
        $file = fopen('php://output', 'w');
        foreach ($csvData as $data) {
            fputcsv($file, explode(',', $data));
        }
        fclose($file);
    }
}
