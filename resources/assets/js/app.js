import CoursesAssignmentsIndex from './courses/assignments/index';
import CoursesAssignmentsForm from './courses/assignments/form';
import CoursesAssignmentsShow from './courses/assignments/show';
import CoursesGradesDashboard from './courses/grades/dashboard';
import CoursesGradesIndex from './courses/grades/index';
import CoursesNotesIndex from './courses/notes/index';
import CoursesNotesForm from './courses/notes/form';

export default {
    el: '#app',
    name: 'app',
    data() {
        return {
            csrfToken: null,
        }
    },
    components: {
        CoursesAssignmentsIndex,
        CoursesAssignmentsForm,
        CoursesAssignmentsShow,
        CoursesGradesDashboard,
        CoursesGradesIndex,
        CoursesNotesIndex,
        CoursesNotesForm,
    }
}