<script setup>
import ChatMessagesForm from './chatMessagesForm.vue';
import messagesContainer from './messagesContainer.vue';
import { ref, onMounted } from 'vue'

const props = defineProps({
    selectedContact: {
        required: true
    },
});

const messages = ref([]);
const nextMessagesPage = ref('');

function getMessages(url, clear = false) {
    axios.get(url)
    .then(res => {
        if (clear)
            messages.value = res['data']['data'];
        else
            messages.value = messages.value.concat(res['data']['data']);
        nextMessagesPage.value = res['data']['next_page_url'];
        console.log(messages.value);
    })
    .catch(error => {
        console.log(error);
    });
}

</script>

<template>
<div class="flex flex-col h-full">
    <div class="bg-gray-800 h-full overflow-y-scroll">
        <messagesContainer @get-messages="getMessages" :next_page_url.sync="nextMessagesPage" :messages.sync="messages" :contact_user2_id.sync="props.selectedContact['contact_user2_id']" />
    </div>
    <div class="mt-1">
        <ChatMessagesForm @get-messages="getMessages" :selected-contact.sync="selectedContact" />
    </div>
</div>
</template>

<style>
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
</style>
