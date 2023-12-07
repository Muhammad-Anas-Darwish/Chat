<script setup>
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import EmptyPageContainer from '@/Components/EmptyPageContainer.vue';
import ChatMessagesForm from '@/Pages/Chats/ChatMessagesForm.vue';
import MessagesContainer from '@/Pages/Chats/MessagesContainer.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted, watch } from 'vue'

const props = defineProps({
    selectedContact: {
        required: true
    },
});

const emit = defineEmits(['reloadContacts', 'toggleContact']);

const messages = ref([]);
const nextMessagesPage = ref('');

function toggleContact() {
    emit('toggleContact', false)
}

function getMessages(url, clear = false) {
    axios.get(url)
    .then(res => {
        if (clear)
            messages.value = res['data']['data']; // clear old messages
        else
            messages.value = messages.value.concat(res['data']['data']); // append new messages to old messages
        nextMessagesPage.value = res['data']['next_page_url'];
        // console.log(messages.value);
    })
    .catch(error => {
        console.log(error);
    });
}

function blockContact() {
    axios.post('/blocks', {
        'banned_id': props.selectedContact.contact_user2_id,
    })
    .then(res => {
        console.log(res);
    })
    .catch(error => {
        console.log(error);
    });
}

function unBlockContact() {
    axios.delete('/blocks/' + props.selectedContact.contact_user2_id)
    .then(res => {
        console.log(res);
    })
    .catch(error => {
        console.log(error);
    });
}

function deleteContact() {
    axios.delete(route('contacts.destroy', props.selectedContact.id))
    .then(res => {
        emit('reloadContacts');
    })
    .catch(error => {
        console.log(error);
    });
}

watch(() => props.selectedContact, (newVal, oldVal) => {
    getMessages('/messages/' + newVal.contact_user2_id, true);
});
</script>

<template>
<div class="flex flex-col h-full">
    <!-- info navbar -->
    <div class="bg-gray-700 flex justify-between items-center py-1 px-3">
        <!-- info -->
        <div class="flex justify-start items-center overflow-hidden">
            <button @click="toggleContact" class="mr-10 text-xl">-</button>
            <img class="w-8 h-8 rounded-full" :src="selectedContact.contact_user2.profile_photo_path ?? selectedContact.contact_user2.profile_photo_url" alt="">
            <div class="flex flex-col ml-3 overflow-hidden">
                <span class="whitespace-nowrap overflow-hidden truncate font-bold text-white">{{ selectedContact.name }}</span>
                <span class="text-gray-400">Connected</span>
            </div>
        </div>
        <!-- Dropdown -->
        <div class="ml-3 relative">
            <Dropdown align="right" width="48">
                <template #trigger>
                    <div class="text-white rounded-full font-bold bg-gray-500">
                        <button type="button" class="w-8 h-8">:</button>
                    </div>
                </template>

                <template #content>
                    <div class="block px-4 py-2 text-xs text-gray-400">
                        Manage Contact
                    </div>

                    <form v-if="selectedContact.is_blocked_by_me" @submit.prevent="unBlockContact">
                        <DropdownLink as="button">
                            Unblock
                        </DropdownLink>
                    </form>
                    <form v-else @submit.prevent="blockContact">
                        <DropdownLink as="button">
                            Block
                        </DropdownLink>
                    </form>

                    <DropdownLink @click="" :href="route('contacts.edit', props.selectedContact['id'])">
                        Edit Contact
                    </DropdownLink>

                    <form @submit.prevent="deleteContact">
                        <DropdownLink as="button">
                            Delete chat
                        </DropdownLink>
                    </form>

                    <div class="border-t border-gray-200 dark:border-gray-600" />

                    <form @submit.prevent="logout">
                        <DropdownLink as="button">
                            Log Out
                        </DropdownLink>
                    </form>
                </template>
            </Dropdown>
        </div>
    </div>
    <!-- main container -->
    <div class="bg-gray-800 h-full overflow-y-scroll">
        <!-- empty container -->
        <EmptyPageContainer v-if="messages.length === 0">
            Send a message
        </EmptyPageContainer>
        <!-- messages container -->
        <MessagesContainer v-else @get-messages="getMessages" :next_page_url.sync="nextMessagesPage" :messages.sync="messages" :contact.sync="props.selectedContact" />
    </div>
    <!-- send message form container -->
    <div v-if="!selectedContact['is_blocked_by_me'] && !selectedContact['is_blocking_me']" class="mt-1">
        <ChatMessagesForm @get-messages="getMessages" :selected-contact.sync="selectedContact" />
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
