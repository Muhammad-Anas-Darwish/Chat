use Illuminate\Support\Facades\Auth;
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import AsideContainer from '@/Pages/Chats/AsideContainer.vue';
import EmptyPageContainer from '@/Components/EmptyPageContainer.vue';
import ChatMessagesContainer from '@/Pages/Chats/ChatMessagesContainer.vue';
import { ref, onMounted, watch, watchEffect } from 'vue';

const props = defineProps({
    userId: {
        required: true
    },
});

const contacts = ref(null);
const selectedContact = ref(false);
const messages = ref([]);
const nextMessagesPage = ref('');

function updateChoicesReceiver(value) {
    messages.value = null;
    value['numberOfUnreadChatMessages'] = 0;
    selectedContact.value = value;
    getMessages(false);
}

const getContacts = () => {
    axios.get(route('contacts.getContacts')).then(response => {
        contacts.value = response.data;
    }).catch(error => {
        console.log(error);
    });
};

const getMessages = (getNextMessages = false) => {
    console.log('get messages');
    if (selectedContact.value['contact_user2_id'] === undefined || getNextMessages && nextMessagesPage.value === null)
        return ;

    let url = (getNextMessages) ? nextMessagesPage.value : route('messages.getMessages', selectedContact.value['contact_user2_id']);

    axios.get(url)
    .then(res => {
        if (getNextMessages)
            messages.value = res['data']['data'].reverse().concat(messages.value); // append new messages to old messages
        else
            messages.value = res['data']['data'].reverse(); // clear old messages
        nextMessagesPage.value = res['data']['next_page_url'];
    })
    .catch(error => {
        console.log(error);
    });
}

function connect() {
    if (selectedContact.value['contact_user2_id']) {
        console.log('connecting success');

        window.Echo.private("chat." + props.userId).listen('.message.new', e => {
            messages.value = messages.value.concat(e['chatMessage']);
        });

        window.Echo.private("contact." + selectedContact.value['id']).listen('.contact.update', e => {
            selectedContact.value['is_blocked_by_me'] = e['contact']['is_blocked_by_me'];
            selectedContact.value['is_blocking_me'] = e['contact']['is_blocking_me'];
        });
    }
}

watch(selectedContact, (val, oldVal) => {
    connect();
});

onMounted(() => {
    getContacts();
});
</script>

<template>
    <AppLayout title="Chats">
        <div class="h-[calc(100vh-4rem)] flex justify-between w-full">
            <AsideContainer :contacts.sync="contacts" :selected-contact="selectedContact" @update-selected-contact="updateChoicesReceiver" />

            <div class="w-full p-1 h-full overflow-x-hidden sm:ml-64">
                <EmptyPageContainer v-if="selectedContact == false" >
                    Select a chat to start messaging
                </EmptyPageContainer>
                <ChatMessagesContainer v-else @reload-contacts="getContacts" @toggle-contact="updateChoicesReceiver" @get-messages="getMessages" :selected-contact.sync="selectedContact" :messages.sync="messages" :nextMessagesPageURL.sync="nextMessagesPage" />
            </div>
        </div>
    </AppLayout>
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
