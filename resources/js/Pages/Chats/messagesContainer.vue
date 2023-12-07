<script setup>
import MessagesSkeleton from '@/Pages/Chats/Skeletons/MessagesSkeleton.vue';
import CenterMessage from '@/Pages/Chats/Parts/CenterMessage.vue';
import Message from '@/Components/Message.vue';
import { ref, onMounted, watch, onUpdated, computed } from 'vue';
import InfiniteLoading from "v3-infinite-loading";
import "v3-infinite-loading/lib/style.css";

const props = defineProps({
    messages: {
        required: true
    },
    contact: {
        required: true
    }
});

const emit = defineEmits(['get-messages']);
const messagesContainer = ref(null);
const messageDate = ref('');

const todayDate = shortFormattedDate(new Date().toISOString());
const yesterdayDate = shortFormattedDate(new Date(new Date().setDate(new Date().getDate() - 1)).toISOString());

function getNextMessages() {
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

onUpdated(() => {
    // messagesContainer.value.scrollIntoView({ behavior: 'smooth', block: 'end' });
});
</script>

<template>
    <div class="flex flex-col-reverse justify-end gap-2 p-2" ref="messagesContainer">
        <MessagesSkeleton v-if="messages.length == 0" />
        <template v-else>
            <CenterMessage v-if="props.contact['is_blocked_by_me']">You blocked this contact.</CenterMessage>
            <CenterMessage v-else-if="props.contact['is_blocking_me'] === 1">You blocked from this contact.</CenterMessage>

            <template v-for="(message, index) in messages" :key="message.id">
                <Message v-if="contact['contact_user2_id'] === message.sender_id" color="bg-blue-500" :message="message.message" :time="`${new Date(message.created_at).getHours()}:${new Date(message.created_at).getMinutes()}`"></Message>
                <Message v-else color="bg-green-700" classes="ml-auto" :message="message.message" :time="`${new Date(message.created_at).getHours()}:${new Date(message.created_at).getMinutes()}`"></Message>

                {{ message.status }}

                <template v-if="index === messages.length - 1 || shortFormattedDate(message.created_at) !== shortFormattedDate(messages[index + 1].created_at)">
                    <CenterMessage>{{ formattedDate(message.created_at) }}</CenterMessage>
                </template>
            </template>
            <InfiniteLoading @infinite="getNextMessages"/>
        </template>
    </div>
</template>
