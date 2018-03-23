export default {
    name: 'courses-notes-index',
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
            /* Get the text field */
            var copyText = this.$refs.slug;
            console.log(copyText);

            /* Select the text field */
            copyText.select();

            /* Copy the text inside the text field */
            document.execCommand("Copy");

            /* Alert the copied text */
            document.getElementById('copyToClipboard').title = "Copied to clipboard!";
        }
    }
}