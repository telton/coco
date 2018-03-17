let Editor = require('tui-editor');
let Viewer = require('tui-editor/dist/tui-editor-Viewer');

export default {
    name: 'courses-grades-dashboard',
    data() {
        return {
            editor: {},
            viewer: {},
            pointsEarned: 0,
            totalPoints: 0,
            grade: 0.00,
        }
    },
    mounted() {
        if (this.$refs.totalPoints) {
            this.totalPoints = this.$refs.totalPoints.value;
        }

        $(this.$refs.deleteGrade).on('click', function (e) {
            // Stop the form from submitting automatically.
            e.preventDefault();
            let form = $(this).parents('form');

            // SweetAlert2 popup.
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                // If confirm, submit the form.
                if (result) {
                    form.submit();
                }
            }).catch(swal.noop); // Catch the cancel option so we don't get console errors.
        });

        $(this.$refs.deleteSubmission).on('click', function (e) {
            // Stop the form from submitting automatically.
            e.preventDefault();
            let form = $(this).parents('form');

            // SweetAlert2 popup.
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                // If confirm, submit the form.
                if (result) {
                    form.submit();
                }
            }).catch(swal.noop); // Catch the cancel option so we don't get console errors.
        });
    },
    computed: {
        computedGrade: function () {
            let value = parseFloat(this.pointsEarned) / parseFloat(this.totalPoints);

            if (isNaN(value) || !isFinite(value)) {
                value = 0;
            }
            
            this.grade = parseFloat(value).toFixed(4);
            return this.grade;
        },
        percentGrade: function () {
            return parseFloat(this.grade * 100).toFixed(2);
        }
    },
    methods: {
        onModalOpen(submissionId) {
            let commentsViewerValue = document.querySelector("#submissionCommentsValue-" + submissionId).value;
            // Check to make sure we've not already created the viewer instance.
            if (!(this.viewer instanceof Viewer)) {
                this.viewer = new Viewer({
                    el: document.querySelector("#submissionCommentsViewer-" + submissionId),
                    height: '500px',
                    initialValue: commentsViewerValue
                });
            }
            
            let commentsEditorValue = document.querySelector("#gradeComments-" + submissionId).value;
            // Check to make sure we've not already created the editor instance.
            if (!(this.editor instanceof Editor)) {
                this.editor = new Editor({
                    el: document.querySelector("#gradeCommentsEditor-" + submissionId),
                    initialEditType: 'wysiwyg',
                    previewStyle: 'tab',
                    height: '200px',
                    initialValue: commentsEditorValue
                });
            }
        },
        onSubmit(submissionId) {
            document.querySelector("#gradeComments-" + submissionId).value = this.editor.getValue();
        },
    }
}