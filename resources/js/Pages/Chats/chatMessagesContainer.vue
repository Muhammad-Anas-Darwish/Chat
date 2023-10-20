<script setup>
import ChatMessagesForm from './chatMessagesForm.vue';
import messagesContainer from './messagesContainer.vue';
import { ref } from 'vue';

const props = defineProps({
    selectedContact: {
        required: true
    },
});
</script>

<template>
<div class="flex flex-col h-full">
    <div class=" bg-gray-800 h-full">
        <messagesContainer />
    </div>
    <div class="mt-3">
        <ChatMessagesForm :selected-contact="selectedContact" />
    </div>
</div>
</template>
