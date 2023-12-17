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
    if (value)
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

        // listen to new message
        window.Echo.private("chat." + props.userId).listen('.message.new', e => {
            messages.value = messages.value.concat(e['chatMessage']);

            // mark message as read
            if (e['chatMessage']['sender_id'] == selectedContact.value['contact_user2_id'])
                axios.get(route('messages.read', e['chatMessage']['id']));
        });

        // listen to read message
        window.Echo.private("contact." + selectedContact.value['id']).listen('.message.read', e =>  {
            let messageId = e['data']['messageId'];

            for (let i = messages.value.length - 1; i >= 0; --i) { // update message status for a read message
                if (messages.value[i]['id'] == messageId) {
                    messages.value[i]['status'] = 'read';
                    break;
                }
            }
        });

        // listen to new contact updates
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
