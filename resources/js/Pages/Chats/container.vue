<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import namesContainer from '@/Pages/Chats/namesContainer.vue';
import notChosenContainer from '@/Pages/Chats/notChosenContainer.vue';
import ChatMessagesContainer from './chatMessagesContainer.vue';
import { ref } from 'vue';

const selectedContact = ref([]);

function getChoicesReceiver(value) {
    selectedContact.value = value;
}
</script>

<template>
    <AppLayout title="Chats">
        <!-- <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Chats
            </h2>
        </template> -->
        <div class="h-full flex justify-between w-full">
            <aside id="default-sidebar" class="fixed top-16 left-0 bottom-0 z-40 w-64 h-full transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
                <div class="h-full px-3 py-4 overflow-y-auto bg-gray-50 dark:bg-gray-800">
                    <ul class="space-y-2 font-medium">
                        <namesContainer @update-selected-contact="getChoicesReceiver" />
                    </ul>
                </div>
            </aside>

            <div class="sm:ml-64 w-full p-1 h-full overflow-x-hidden">
                <notChosenContainer v-if="selectedContact == false" />
                <chatMessagesContainer v-else :selected-contact.sync="selectedContact" />
            </div>
        </div>
    </AppLayout>
</template>
