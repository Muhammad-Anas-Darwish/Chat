<script setup>
import { ref, onMounted, watch, onUpdated} from 'vue';

const props = defineProps({
    messages: {
        required: true
    },
    next_page_url: {
        required: true
    },
    contact_user2_id: {
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
    emit('get-messages', '/messages/' + props.contact_user2_id, true);
});

onUpdated(() => {
    messagesContainer.value.scrollIntoView({ behavior: 'smooth', block: 'end' });
});

watch(() => props.contact_user2_id, (newVal, oldVal) => {
    emit('get-messages', '/messages/' + newVal, true);
});
</script>

<template>
    <div class="flex flex-col-reverse justify-end gap-2 px-2" ref="messagesContainer">
        <template v-for="message in messages" :key="message.id">
            <p v-if="contact_user2_id === message.sender_id" class="rounded-lg text-2xl bg-blue-500 text-white px-2 break-words max-w-[85%] md:max-w-[70%] w-fit">{{ message.message }}</p>
            <p v-else class="rounded-lg text-2xl bg-green-700 text-white px-2 break-words  max-w-[85%] md:max-w-[70%] w-fit ml-auto">{{ message.message }}</p>
        </template>
    </div>
</template>
