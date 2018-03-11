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
            this.viewer = new Viewer({
                el: document.querySelector("#submissionCommentsViewer-" + submissionId),
                height: '500px',
                initialValue: commentsViewerValue
            });
            
            let commentsEditorValue = document.querySelector("#gradeComments-" + submissionId).value
            this.editor = new Editor({
                el: document.querySelector("#gradeCommentsEditor-" + submissionId),
                initialEditType: 'wysiwyg',
                previewStyle: 'tab',
                height: '200px',
                initialValue: commentsEditorValue
            });
        },
        onSubmit(submissionId) {
            document.querySelector("#gradeComments-" + submissionId).value = this.editor.getValue();
        },
    }
}