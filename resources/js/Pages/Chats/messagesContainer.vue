<script setup>
import MessagesSkeleton from '@/Pages/Chats/Skeletons/MessagesSkeleton.vue';
import CenterMessage from '@/Pages/Chats/Parts/CenterMessage.vue';
import { ref, onMounted, watch, onUpdated} from 'vue';

const props = defineProps({
    messages: {
        required: true
    },
    next_page_url: {
        required: true
    },
    contact: {
        required: true
    }
});

const emit = defineEmits(['get-messages']);
const messagesContainer = ref(null);

function getNextMessages() {
    if (next_page_url == null)
        return ;

    emit('get-messages', next_page_url.value);
}

onMounted(() => {
    emit('get-messages', '/messages/' + props.contact['contact_user2_id'], true);
});

onUpdated(() => {
    messagesContainer.value.scrollIntoView({ behavior: 'smooth', block: 'end' });
});
</script>

<template>
    <div class="flex flex-col-reverse justify-end gap-2 p-2" ref="messagesContainer">
        <MessagesSkeleton v-if="messages === null" />
        <template v-else>
            <CenterMessage v-if="props.contact['is_blocked_by_me']">You blocked this contact.</CenterMessage>
            <CenterMessage v-else-if="props.contact['is_blocking_me'] === 1">You blocked from this contact.</CenterMessage>
            <template v-for="message in messages" :key="message.id">
                    <p v-if="contact['contact_user2_id'] === message.sender_id" class="rounded-lg text-2xl bg-blue-500 text-white px-2 break-words max-w-[85%] md:max-w-[70%] w-fit">{{ message.message }}</p>
                    <p v-else class="rounded-lg text-2xl bg-green-700 text-white px-2 break-words  max-w-[85%] md:max-w-[70%] w-fit ml-auto">{{ message.message }}</p>
            </template>
        </template>
    </div>
</template>
