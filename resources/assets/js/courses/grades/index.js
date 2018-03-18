let Viewer = require('tui-editor/dist/tui-editor-Viewer');

export default {
    name: 'courses-grades-index',
    data() {
        return {
            commentsViewer: {},
        }
    },
    methods: {
        onModalOpen() {
            let viewGradeCommentsValue = this.$refs.viewGradeComments.value;
            // Check to make sure we've not already created the viewer instance.
            if (!(this.commentsViewer instanceof Viewer)) {
                this.commentsViewer = new Viewer({
                    el: this.$refs.gradeCommentsViewer,
                    height: '500px',
                    initialValue: viewGradeCommentsValue
                });
            }
        }
    }
}