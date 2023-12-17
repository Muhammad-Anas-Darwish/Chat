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
    messages: {
        required: true
    },
    nextMessagesPageURL: {
        required: true
    },
});

const emit = defineEmits(['reloadContacts', 'toggleContact', 'getMessages']);

function toggleContact() {
    emit('toggleContact', false)
}

function blockContact() {
    axios.post(route('blocks.store'), {
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
    axios.delete(route('blocks.destroy', props.selectedContact.contact_user2_id))
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

function getMessages() {
    emit('getMessages', true);
}
</script>

<template>
<div class="flex flex-col h-full">
    <!-- info navbar -->
    <div class="bg-gray-700 flex justify-between items-center py-1 px-3">
        <!-- info -->
        <div class="flex justify-start items-center overflow-hidden">
            <button @click="toggleContact" class="mr-2 text-xl">
                <svg class="w-6 h-6 fill-gray-500" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="800px" height="800px" viewBox="0 0 72 72" enable-background="new 0 0 72 72" xml:space="preserve">
                    <g>
                        <path d="M48.252,69.253c-2.271,0-4.405-0.884-6.011-2.489L17.736,42.258c-1.646-1.645-2.546-3.921-2.479-6.255
                            c-0.068-2.337,0.833-4.614,2.479-6.261L42.242,5.236c1.605-1.605,3.739-2.489,6.01-2.489c2.271,0,4.405,0.884,6.01,2.489
                            c3.314,3.314,3.314,8.707,0,12.021L35.519,36l18.743,18.742c3.314,3.314,3.314,8.707,0,12.021
                            C52.656,68.369,50.522,69.253,48.252,69.253z M48.252,6.747c-1.202,0-2.332,0.468-3.182,1.317L21.038,32.57
                            c-0.891,0.893-0.833,2.084-0.833,3.355c0,0.051,0,0.101,0,0.151c0,1.271-0.058,2.461,0.833,3.353l24.269,24.506
                            c0.85,0.85,1.862,1.317,3.063,1.317c1.203,0,2.273-0.468,3.123-1.317c1.755-1.755,1.725-4.61-0.03-6.365L31.292,37.414
                            c-0.781-0.781-0.788-2.047-0.007-2.828L51.438,14.43c1.754-1.755,1.753-4.61-0.001-6.365C50.587,7.215,49.454,6.747,48.252,6.747z"
                            />
                    </g>
                </svg>
            </button>
            <img class="w-8 h-8 rounded-full" :src="selectedContact.contact_user2.profile_photo_path ?? selectedContact.contact_user2.profile_photo_url" alt="">
            <div class="flex flex-col ml-3 overflow-hidden">
                <span class="whitespace-nowrap overflow-hidden truncate font-bold text-white">{{ selectedContact.name }}</span>
                <span class="text-gray-400">Contact</span>
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
    <div v-if="messages !== null && messages.length === 0" class="bg-gray-800 h-full">
        <!-- empty container -->
        <EmptyPageContainer >
            Send a message
        </EmptyPageContainer>
    </div>

    <!-- messages container -->
    <MessagesContainer v-else @get-messages="getMessages" :messages.sync="messages" :contact.sync="props.selectedContact" :nextMessagesPageURL="nextMessagesPageURL" />

    <!-- send message form container -->
    <div v-if="!selectedContact['is_blocked_by_me'] && !selectedContact['is_blocking_me']" class="mt-1">
        <ChatMessagesForm :selected-contact.sync="selectedContact" />
    </div>
</div>
</template>
