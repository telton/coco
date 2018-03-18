export default {
    name: 'courses-assignments-index',
    data() {
        return {
            viewer: {},
        }
    },
    methods: {
        onDelete(id) {
            let form = document.querySelector("#deleteForm-" + id);

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
        } 
    }
}