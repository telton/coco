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
    }
}