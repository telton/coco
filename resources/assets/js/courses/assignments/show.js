let Viewer = require('tui-editor/dist/tui-editor-Viewer');

export default {
    name: 'courses-assignments-show',
    data() {
        return {
            viewer: {},
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
    }
}