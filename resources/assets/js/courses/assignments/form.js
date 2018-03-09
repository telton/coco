import flatPickr from 'vue-flatpickr-component';
let Editor = require('tui-editor');

export default {
    name: 'courses-assignments-form',
    props: {
        dueDate: {
            type: String,
            default: '',
        },
        displayDate: {
            type: String,
            default: '',
        },
        attachments: {}
    },
    data() {
        let self = this;
        let displayDateMin = new Date;
        
        if (this.displayDate !== '') {
            displayDateMin = this.displayDate;
        }

        return {
            editor: {},
            inputs: {
                due_date: this.dueDate,
                display_date: this.displayDate
            },
            displayDateConfig: {
                minDate: displayDateMin,
                inline: true,
                enableTime: true
            },
            dueDateConfig: {
                minDate: new Date,
                inline: true,
                enableTime: true
            },
            uploads: [],
            dropzone: false,
        }
    },
    watch: {
        inputs: {
            handler(newValue) {
                if (newValue.dueDate !== '') {
                    this.displayDateConfig = {
                        maxDate: newValue.dueDate,
                        inline: this.dueDateConfig.inline,
                        enableTime: this.dueDateConfig.enableTime,
                    }
                }
            }
        }
    },
    mounted() {
        let descriptionValue = this.$refs.description.value;

        this.editor = new Editor({
            el: this.$refs.descriptionEditor,
            initialEditType: 'wysiwyg',
            previewStyle: 'tab',
            height: '300px',
            initialValue: descriptionValue
        });

        // Handle drag and drop.
        $(this.$refs.dropzone).on('drag dragstart dragend dragover dragenter dragleave drop', function (e) {
            e.preventDefault();
            e.stopPropagation();
        }).on('dragover dragenter', function () {
            self.dropzone = true;
        }).on('dragleave dragend drop', function () {
            self.dropzone = false;
        }).on('drop', function (e) {
            self.dropUpload(e.originalEvent.dataTransfer.files);
        });
    },
    methods: {
        onSubmit() {
            this.$refs.description.value = this.editor.getValue();
        },
        dropUpload(files) {
            let id = this.uploads.length;
            this.uploads.push({
                id: id,
                files: files,
            });

            let input = document.createElement('input');
            input.setAttribute('id', 'upload-' + id);
            input.setAttribute('multiple', 'multiple');
            input.setAttribute('type', 'file');
            input.setAttribute('name', 'uploads[]');
            input.files = files;
            console.log(input);
            this.$refs.files.append(input);
        },
        addUpload() {
            let self = this,
                input = document.createElement('input');

            input.setAttribute('multiple', 'multiple');
            input.setAttribute('type', 'file');
            input.setAttribute('name', 'uploads[]');

            $(input).click();
            $(input).change(function () {
                let id = self.uploads.length;
                self.uploads.push({
                    id: id,
                    files: input.files,
                });

                input.setAttribute('id', 'upload-' + id);
            });

            this.$refs.files.append(input);
        },
        removeUpload(upload) {
            let self = this,
                index = this.uploads.indexOf(upload);

            // SweetAlert2 popup.
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, remove it!',
            }).then((result) => {
                // If confirm, remove the uploaded file.
                if (result) {
                    self.uploads.splice(index, 1);
                    self.$refs.files.removeChild(document.getElementById('upload-' + upload.id));
                }
            }).catch(swal.noop); // Catch the cancel option so we don't get console errors.
        },
        removeAttachment(attachment) {
            let self = this;

            // SweetAlert2 popup.
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, remove it!',
            }).then((result) => {
                // If confirm, remove the attachment.
                if (result) {
                    self.attachments.splice(self.attachments.indexOf(attachment), 1);
                }
            }).catch(swal.noop); // Catch the cancel option so we don't get console errors.
        }
    },
    components: {
        flatPickr
    }
}