<template>
    <ul class="chat" ref="chat">
        <li class="left clearfix" v-for="message in messages">
            <div class="clearfix">
                <div class="chat-heading">
                    <strong>{{ message.user.name }}</strong>
                </div>
                <div class="chat-content">
                    <p class="chat-message">
                        {{ message.message }}
                    </p>
                    <p class="chat-timestamp">
                        {{ message.created_at | date }}
                    </p>
                </div>
            </div>
        </li>
    </ul>
</template>

<script>
    import ChatStore from './store';
    import Moment from 'moment';

    export default {
        props: [
            'slug',
        ],
        mounted() {
            ChatStore.$emit('mounted', {
                slug: this.slug
            });
        },
        computed: {
            messages() {
                return ChatStore.messages;
            }
        },
        filters: {
            date(value) {
                return Moment(value).format('MM/DD/YYYY hh:mm A');
            }
        },
    };
</script>