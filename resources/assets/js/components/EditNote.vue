<template>
    <div class="notes">
            <div class="notes-title form-group">
                <label for="title" class="control-label"><strong>Title:</strong></label>
                <input type="text" class="form-control" v-model="title" @keydown="editingNote()">
            </div>

            <div class="form-group">
                <label for="body" class="control-label"><strong>Body:</strong></label>
                <div id="bodyEditor" ref="bodyEditor" @keydown="editingNote()"></div>
                <input type="hidden" name="body" id="body" v-model="body" ref="body">
            </div>

            <button class="btn btn-primary pull-right" @click="updateNote()"><i class="fa fa-save"></i> Save</button>
            <span class="pull-right alert alert-success note-alert" v-text="status" v-if="status != ''"></span>

            <p>
                Users editing this note:  <span class="badge">{{ usersEditing.length }}</span>
                
            </p>
    </div>
</template>

<script>
    let Editor = require('tui-editor');

    export default {
        props: [
            'note',
            'course',
        ],

        data() {
            return {
                title: this.note.title,
                body: this.note.body,
                usersEditing: [],
                status: ''
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

            Echo.join(`courses.${this.course.slug}.notes.${this.note.slug}`)
                .here(users => {
                    this.usersEditing = users;
                })
                .joining(user => {
                    this.usersEditing.push(user);
                })
                .leaving(user => {
                    this.usersEditing = this.usersEditing.filter(u => u != user);
                })
                .listenForWhisper('editing', (e) => {
                    this.title = e.title;
                    this.body = e.body;

                    this.editor.setValue(e.body, false);
                })
                .listenForWhisper('saved', (e) => {
                    this.status = e.status;

                    // clear is status after 1s
                    setTimeout(() => {
                        this.status = '';
                    }, 1500);
                });
        },

        methods: {
            editingNote() {
                let channel = Echo.join(`courses.${this.course.slug}.notes.${this.note.slug}`);

                // show changes after 1s
                setTimeout(() => {
                    channel.whisper('editing', {
                        title: this.title,
                        body: this.editor.getValue()
                    });
                }, 500);
            },
            updateNote() {
                let note = {
                    title: this.title, 
                    body:  this.editor.getValue()
                };

                // persist to database
                axios.patch(`edit`, note)
                    .then(response => {
                        // show saved status
                        this.status = response.data;

                        // clear is status after 1s
                        setTimeout(() => {
                            this.status = '';
                        }, 1500);

                        // show saved status to others
                        Echo.join(`courses.${this.course.slug}.notes.${this.note.slug}`)
                            .whisper('saved', {
                                status: response.data
                            });
                    });
            }
        }
    }
</script>