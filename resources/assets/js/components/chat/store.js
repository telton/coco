import Vue from 'vue';

const ChatStore = new Vue({
    data: {
        slug: '',
        loading: false,
        messages: []
    },
    created() {
        this.$on('mounted', (data) =>  {
            this.slug = data.slug
            this.getMessages();

            Echo.private(`courses.${this.slug}.chat`)
                .listen('MessageSent', (e) => {
                    this.messages.push({
                        message: e.message.message,
                        user: e.user
                    });
                });

            setTimeout(() => {
                this.updateScrollbar();
            }, 1000);
        });

        this.$on('messagesent', (data) => {
            this.addMessage(data);

            this.updateScrollbar();
        });
    },
    methods: {
        getMessages() {
            this.loading = true;

            axios.get(`/courses/${this.slug}/chat/messages`).then(response => {
                this.messages = response.data;
                this.loading = false;

                this.updateScrollbar();
            });
        },
        addMessage(message) {
            this.loading = true;
            this.messages.push(message);

            axios.post(`/courses/${this.slug}/chat/messages`, message);
            setTimeout(() => {
                this.updateScrollbar();
            }, 5);
        },
        updateScrollbar() {
            // Scroll to bottom of chat.
            let chatBody = document.getElementById('chatBody');
            chatBody.scrollTop = chatBody.scrollHeight;
        }
    }
});

export default ChatStore;