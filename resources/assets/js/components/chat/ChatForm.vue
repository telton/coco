<template>
    <div class="input-group">
        <input id="btn-input" type="text" name="message" class="form-control input-sm" placeholder="Type your message here..." 
            v-model="newMessage" @keyup.enter="sendMessage" autofocus>

        <span class="input-group-btn">
            <button class="btn btn-primary btn-sm" id="btn-chat" @click="sendMessage">
                Send
            </button>
        </span>
    </div>
</template>

<script>
    import ChatStore from './store';

    export default {
        props: [
            'user',
            'slug',
        ],
        data() {
            return {
                newMessage: ''
            }
        },
        mounted() {
            ChatStore.slug = this.slug;
        },
        methods: {
            sendMessage() {
                if (this.newMessage != '') {
                   ChatStore.$emit('messagesent', {
                        user: this.user,
                        message: this.newMessage,
                    });

                    this.newMessage = '';
                    document.getElementById("btn-input").focus(); 
                }               
            }
        }    
    }
</script>