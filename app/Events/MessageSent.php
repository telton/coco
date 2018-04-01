<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use App\Models\User;
use App\Models\Courses\Message;
use App\Models\Courses\Course;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * User that sent the message.
     *
     * @var User
     */
    public $user;

    /**
     * Message details.
     *
     * @var Message
     */
    public $message;

    /**
     * The course.
     *
     * @var Course
     */
    public $course;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, Message $message, Course $course)
    {
        $this->user = $user;
        $this->message = $message;
        $this->course = $course;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel("courses.{$this->course->slug}.chat");
    }
}
