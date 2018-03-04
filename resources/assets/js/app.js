import CoursesAssignmentsIndex from './courses/assignments/index';
import CoursesAssignmentsForm from './courses/assignments/form';
import CoursesAssignmentsShow from './courses/assignments/show';

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
        CoursesAssignmentsShow
    }
}