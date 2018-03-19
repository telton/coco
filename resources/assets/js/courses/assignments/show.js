let Viewer = require('tui-editor/dist/tui-editor-Viewer');
let Editor = require('tui-editor');

export default {
    name: 'courses-assignments-show',
    data() {
        return {
            descriptionViewer: {},
            commentsViewer: {},
            gradeCommentsViewer: {},
            editor: {},
            attachments: [],
            uploads: [],
            dropzone: false
        }
    },
    mounted() {
        let self = this;
        let descriptionValue = this.$refs.description.value;
        this.descriptionViewer = new Viewer({
            el: this.$refs.descriptionViewer,
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
        onModalOpen() {
            let submitCommentsValue = this.$refs.submitComments.value;
            // Check to make sure we've not already created the editor instance.
            if (!(this.editor instanceof Editor)) {
                this.editor = new Editor({
                    el: this.$refs.commentsEditor,
                    initialEditType: 'wysiwyg',
                    previewStyle: 'tab',
                    height: '200px',
                    initialValue: submitCommentsValue
                });
            }

            if (this.$refs.viewComments) {
                let viewCommentsValue = this.$refs.viewComments.value;
                // Check to make sure we've not already created the viewer instance.
                if (!(this.commentsViewer instanceof Viewer)) {
                    this.commentsViewer = new Viewer({
                        el: this.$refs.commentsViewer,
                        height: '500px',
                        initialValue: viewCommentsValue
                    });
                }
            }
            
            if (this.$refs.gradeComments) {
                let viewGradeCommentsValue = this.$refs.viewGradeComments.value;
                // Check to make sure we've not already created the viewer instance.
                if (!(this.gradeCommentsViewer instanceof Viewer)) {
                    this.gradeCommentsViewer = new Viewer({
                        el: this.$refs.gradeCommentsViewer,
                        height: '500px',
                        initialValue: viewGradeCommentsValue
                    });
                }
            }
            
        },
        onSubmit() {
            this.$refs.submitComments.value = this.editor.getValue();
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
    }
}