let Viewer = require('tui-editor/dist/tui-editor-Viewer');

export default {
    name: 'courses-notes-show',
    data() {
        return {
            viewer: {},
        }
    },
    mounted() {
        let bodyViewerValue = this.$refs.body.value;
        // Check to make sure we've not already created the viewer instance.
        if (!(this.viewer instanceof Viewer)) {
            this.iewer = new Viewer({
                el: this.$refs.bodyViewer,
                height: '700px',
                initialValue: bodyViewerValue
            });
        }
    },
    methods: {
        onDelete() {
            let form = this.$refs.deleteNoteForm;

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
        },
        copyToClipboard() {
            // Get the text field.
            var copyText = this.$refs.slug;

            // Select the text field.
            copyText.select();

            // Copy the text inside the text field.
            document.execCommand("Copy");

            // Destroy the original tooltip, change the title, and reenable the tooltip.
            $('#copyToClipboard').tooltip('dispose').attr('title', 'Copied!').tooltip('show');
        }
    }
}