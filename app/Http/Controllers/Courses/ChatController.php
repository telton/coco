<?php

namespace App\Http\Controllers\Courses;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Courses\Course;
use Illuminate\Support\Facades\Auth;
use App\Models\Courses\Message;
use App\Events\MessageSent;

class ChatController extends Controller
{
    /**
     * ChatController constructor.
     *
     * @author Tyler Elton <telton@umflint.edu>
     */
    public function __construct()
    {
        parent::__construct();
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
        $messages = Message::where('channel', $slug)->with('user')->get();

        $this->breadcrumb->addCrumb(strtoupper($course->slug), route('courses.show', $course->slug));
        $this->breadcrumb->addCrumb('Chat', route('courses.chat.index', $course->slug));
        return view('courses.chat.index', [
            'course'   => $course,
            'messages' => $messages,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param string                     $slug
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, string $slug)
    {
        $user = Auth::user();
        $course = Course::where('slug', $slug)->first();

        $message = Auth::user()->messages()->create([
            'message' => $request->input('message'),
            'channel' => $slug,
        ]);

        broadcast(new MessageSent($user, $message, $course))->toOthers();

        return [
            'status' => 'Message sent!',
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function show(string $slug)
    {
        return Message::where('channel', $slug)->with('user')->get();
    }
}
