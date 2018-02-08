import CoursesAssignmentsForm from './courses/assignments/form';

export default {
    el: '#app',
    name: 'app',
    data() {
        return {
            csrfToken: null,
        }
    },
    components: {
        CoursesAssignmentsForm
    }
}