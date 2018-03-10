let Editor = require('tui-editor');

export default {
    name: 'courses-grades-dashboard',
    props: {

    },
    data() {
        return {
            editor: {},
        }
    },
    mounted() {
        let commentsValue = this.$refs.comments.value;

        this.editor = new Editor({
            el: this.$refs.commentsEditor,
            initialEditType: 'wysiwyg',
            previewStyle: 'tab',
            height: '300px',
            initialValue: commentsValue
        });
    },
    methods: {
        onModalOpen() {
            let commentsValue = this.$refs.comments.value;
            
            // Check to make sure we've not already created the editor instance.
            if (!(this.editor instanceof Editor)) {
                this.editor = new Editor({
                    el: this.$refs.commentsEditor,
                    initialEditType: 'wysiwyg',
                    previewStyle: 'tab',
                    height: '200px',
                    initialValue: commentsValue
                });
            }
        },
        onSubmit() {
            this.$refs.comments.value = this.editor.getValue();
        },
    }
}