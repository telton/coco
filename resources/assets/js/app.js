import CoursesAssignmentsIndex from './courses/assignments/index';
import CoursesAssignmentsForm from './courses/assignments/form';
import CoursesAssignmentsShow from './courses/assignments/show';
import CoursesGradesDashboard from './courses/grades/dashboard';
import CoursesGradesIndex from './courses/grades/index';
import CoursesNotesIndex from './courses/notes/index';
import CoursesNotesForm from './courses/notes/form';
import CoursesNotesShow from './courses/notes/show';

export default {
    el: '#app',
    name: 'app',
    components: {
        CoursesAssignmentsIndex,
        CoursesAssignmentsForm,
        CoursesAssignmentsShow,
        CoursesGradesDashboard,
        CoursesGradesIndex,
        CoursesNotesIndex,
        CoursesNotesForm,
        CoursesNotesShow,
    },
}