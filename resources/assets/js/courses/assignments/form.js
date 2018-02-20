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
        }
    },
    data() {
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
            }
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
            el: document.querySelector('#descriptionEditor'),
            initialEditType: 'wysiwyg',
            previewStyle: 'tab',
            height: '300px',
            initialValue: descriptionValue
        });
    },
    methods: {
        onSubmit() {
            this.$refs.description.value = this.editor.getValue();
        }
    },
    components: {
        flatPickr
    }
}