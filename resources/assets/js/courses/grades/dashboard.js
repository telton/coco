let Editor = require('tui-editor');
let Viewer = require('tui-editor/dist/tui-editor-Viewer');

export default {
    name: 'courses-grades-dashboard',
    props: {

    },
    data() {
        return {
            editor: {},
            viewer: {},
        }
    },
    mounted() {
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