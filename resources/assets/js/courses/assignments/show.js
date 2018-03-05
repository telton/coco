let Viewer = require('tui-editor/dist/tui-editor-Viewer');
let Editor = require('tui-editor');

export default {
    name: 'courses-assignments-show',
    data() {
        return {
            viewer: {},
            editor: {},
        }
    },
    mounted() {
        let descriptionValue = this.$refs.description.value;
        this.viewer = new Viewer({
            el: document.querySelector('#descriptionViewer'),
            height: '500px',
            initialValue: descriptionValue
        });

        $(this.$refs.delete).on('click', function (e) {
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
    methods: {
        onModalOpen() {
            let commentsValue = this.$refs.comments.value;
            // Check to make sure we've not already created the editor instance.
            if (!(this.editor instanceof Editor)) {
                this.editor = new Editor({
                    el: document.querySelector('#commentsEditor'),
                    initialEditType: 'wysiwyg',
                    previewStyle: 'tab',
                    height: '200px',
                    initialValue: commentsValue
                });
            }
        },
        onSubmit() {
            this.$refs.description.value = this.editor.getValue();
        }
    }
}