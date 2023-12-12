<script setup>
import MessagesSkeleton from '@/Pages/Chats/Skeletons/MessagesSkeleton.vue';
import CenterMessage from '@/Pages/Chats/Parts/CenterMessage.vue';
import Message from '@/Components/Message.vue';
import { ref, onMounted, watch, onUpdated, computed, onBeforeUpdate } from 'vue';
import InfiniteLoading from "v3-infinite-loading";
import "v3-infinite-loading/lib/style.css";

const props = defineProps({
    messages: {
        required: true
    },
    contact: {
        required: true
    },
    nextMessagesPageURL: {
        required: true
    },
});

const emit = defineEmits(['get-messages']);
const messagesContainer = ref(null);
const scrollHeight = ref(0);

const todayDate = shortFormattedDate(new Date().toISOString());
const yesterdayDate = shortFormattedDate(new Date(new Date().setDate(new Date().getDate() - 1)).toISOString());

function getNextMessages() {
    console.log("get next messages");
    emit('get-messages');
}

function shortFormattedDate(date) {
    return `${new Date(date).getDate()} ${new Date(date).toLocaleString('default', { month: 'short' })} ${new Date(date).getFullYear()}`;
};

function formattedDate(date) {
    date = shortFormattedDate(date);

    if (date === todayDate)
        return "Today";
    if (date === yesterdayDate)
        return "Yesterday";
    return date;
};

onBeforeUpdate(() => {
    scrollHeight.value = messagesContainer.value.scrollHeight;
});

watch(() => props.messages, (newMessages, oldMessages) => {
    if (oldMessages === null || newMessages === null) {
        messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight;
    }
    else {
        messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight - scrollHeight.value - messagesContainer.value.scrollTop;
    }
});
</script>

<template>
    <div class="bg-gray-800 h-full overflow-y-scroll" ref="messagesContainer">
        <div class="flex flex-col justify-end gap-2 p-2">
            <MessagesSkeleton v-if="messages === null" />
            <template v-else>
                <InfiniteLoading v-if="nextMessagesPageURL" @infinite="getNextMessages"/>

                <template v-for="(message, index) in messages" :key="message.id">
                    <template v-if="index === 0 || shortFormattedDate(message.created_at) !== shortFormattedDate(messages[index - 1].created_at)">
                        <CenterMessage>{{ formattedDate(message.created_at) }}</CenterMessage>
                    </template>
                    <Message v-if="contact['contact_user2_id'] === message.sender_id" color="bg-gray-700" :message="message.message" :time="`${new Date(message.created_at).getHours()}:${new Date(message.created_at).getMinutes()}`"></Message>
                    <Message v-else color="bg-gray-500" classes="ml-auto" :message="message.message" :time="`${new Date(message.created_at).getHours()}:${new Date(message.created_at).getMinutes()}`"></Message>
                </template>

                <CenterMessage v-if="props.contact['is_blocked_by_me']">You blocked this contact.</CenterMessage>
                <CenterMessage v-else-if="props.contact['is_blocking_me']">You blocked from this contact.</CenterMessage>
            </template>
        </div>
    </div>
</template>


<style>
    /* start scrollbar */
    /* width */
    .overflow-y-scroll::-webkit-scrollbar {
        width: 3px;
    }

    /* Track */
    .overflow-y-scroll::-webkit-scrollbar-track {
        background: transparent;
        border-radius: 1rem;
    }

    /* Handle */
    .overflow-y-scroll::-webkit-scrollbar-thumb {
        --tw-text-opacity: 1;
        background: rgb(156 163 175 / var(--tw-text-opacity));
        border-radius: 1rem;
    }

    /* Handle on hover */
    .overflow-y-scroll::-webkit-scrollbar-thumb:hover {
        background: rgb(156 163 175 / var(--tw-text-opacity));
    }
    /* end scrollbar */
</style>
