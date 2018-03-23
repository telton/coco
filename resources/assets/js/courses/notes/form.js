let Editor = require('tui-editor');

export default {
    name: 'courses-notes-form',
    data() {
        return {
            editor: {},
        }
    },
    mounted() {
        let bodyEditorValue = this.$refs.body.value;
        // Check to make sure we've not already created the editor instance.
        if (!(this.editor instanceof Editor)) {
            this.editor = new Editor({
                el: this.$refs.bodyEditor,
                initialEditType: 'wysiwyg',
                previewStyle: 'tab',
                height: '700px',
                initialValue: bodyEditorValue,
            });
        }
    },
    methods: {
        onSubmit() {
            this.$refs.body.value = this.editor.getValue();
        }
    }
}