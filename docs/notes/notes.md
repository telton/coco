# Main Components

### User Authentication - __DONE__

* Users will be able to sign in & register.

### 5 Different Roles - __DONE__

* Student
* Grader
* Tutor
* Instructor
* Admin

__Privilege hierarchy (least permissions to most):__

Student -> Grader -> Tutor -> Instructor -> Admin

### Ability to Add Classes - __DONE__

* Admins can add classes, sections, and terms. Assign instructor(s) to a class and section for a given term.

* Instructors will be able to assign students to class & section they are assigned to teach for that term.

* Instructors will also be able to add grader(s) and tutors(s) for that class & section.

* Students will only be able to see the classes they are assigned (registered for).

### Homework Assignments - __DONE__

* Instructors are able to add their homework assignments, along with their due dates.

* Students can submit their completed assignments before the due date.
    * After the due date, students are not able to submit.

* Graders and tutors can see student's assignments, and add grades. Grades must be approved by an instructor after a grader has added them.

* Tutors can host their own chat sessions, akin to a virtual SI session.

### Exporting Grades for External Use - __DONE__

* Instructors will be able to export __all of the student's__ grades to a file format (CSV, for example) for use when making calculations (mean grade, for example).

* Students will have the ability to export their __own__ grades for a course, so they can make their own calculations (figuring out a weighted grade, for example).

### Turn Discussion Board into Live Chat Feed - __DONE__

* Instead of a static discussion board, the chat will be continuously refreshed without the need to refresh the page itself.

* Chats are meant to be self contained (1 for each lecture, for example).

* ~~Instructors will have the ability to "shut down" a feed after they deem that it is no longer applicable to talk about a certain thing.~~ - __NOT IMPLEMENTED__

### Collaborative Note-taking __DONE__

Users can have the ability to collaborate on notes. Instructors may be able to comment on notes to offer clarification (on a question of a misunderstood topic, for example) on a student's notes.

### ~~Opt-out Notification System~~ - __NOT IMPLEMENTED__

* Students will recieve emails when homework assignments are released.

* Students will recieve an email when a homework assignment has been graded.

* All notifications will have the ability to be opted out, so users won't recieve them.

---

# Possible Additions

# Reports

* Possible reports would be tutoring statistics. I know that the university is interested in such stats, but since they use 3 different systems (or did) they are having a hard time with that.

### Tutor Rating System

* Students can rate tutors, and give feedback to them.

* Instructors and only the tutor being reviewed may see the final data. Students' ratings/comments are confidential, no one knows who submitted the review.

### Add Live Video Feed to Tutoring Sessions

* Along with live text chat, tutors can also set up a live video feed to present and help with student learning.

### Collaborative Whiteboard

* Users can "draw" on a virtual whiteboard to demonstrate ideas, or to solve equations/problems.

### API to Automate the Process

* Instead of entering in courses, sections, terms, and assigning students & instructors to them manually, have some sort of automation process that does this.
    * Mitigates errors when entering this data in manually.
    * Can't have access to live user data, but we could impliment our own version of an AD (active directory) system to emulate this process.

### Expand the Notification System

* Allow for SMS notifications?

* Expand different notifications to instructors, tutors, and graders.

### Custom Themes

* Allow instructors to choose a style for each course (like how BB does now).
