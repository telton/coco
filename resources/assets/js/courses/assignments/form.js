var Editor = require('tui-editor');

export default {
    name: 'courses-assignments-form',
    data() {
        return {
            editor: {}
        }
    },
    mounted() {
        this.editor = new Editor({
            el: document.querySelector('#descriptionEditor'),
            initialEditType: 'wysiwyg',
            previewStyle: 'tab',
            height: '300px',
        });


    },
    methods: {
        onSubmit() {
            document.querySelector('#description').value = this.editor.getValue();
        }
    }
}