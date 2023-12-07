<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import AsideContainer from '@/Pages/Chats/AsideContainer.vue';
import EmptyPageContainer from '@/Components/EmptyPageContainer.vue';
import ChatMessagesContainer from '@/Pages/Chats/ChatMessagesContainer.vue';
import { ref, onMounted, watch, watchEffect } from 'vue';

const contacts = ref(null);
const selectedContact = ref(false);
const messages = ref([]);
const nextMessagesPage = ref('');

function updateChoicesReceiver(value) {
    selectedContact.value = value;
}

const getContacts = () => {
    axios.get(route('contacts.getContacts')).then(response => {
        contacts.value = response.data;
    }).catch(error => {
        console.log(error);
    });
};

const getMessages = (getNextMessages = false) => {
    if (selectedContact.value['contact_user2_id'] === undefined)
        return;

    axios.get(route('messages.getMessages', selectedContact.value['contact_user2_id']))
    .then(res => {
        if (getNextMessages)
            messages.value = messages.value.concat(res['data']['data']); // append new messages to old messages
        else
            messages.value = res['data']['data']; // clear old messages
        nextMessagesPage.value = res['data']['next_page_url'];
    })
    .catch(error => {
        console.log(error);
    });
}

watch(selectedContact, (newVal, oldVal) => {
    getMessages(false);
});

onMounted(() => {
    getContacts();
});

////////

// const messages = ref([]);

// function getMessages(url) {
//     axios.get(url)
//     .then(res => {
//         messages.value = res['data']['data'];
//         console.log("get messages");
//     })
//     .catch(error => {
//         console.log(error);
//     });
// }

// function connect() {
//     console.log('connect');
//     if (selectedContact.value['contact_user2_id']) {
//         console.log('connecting success')
//         getMessages('/messages/' + selectedContact.value['contact_user2_id']);

//         window.Echo.private("chat.8").listen('.message.new', e => {
//             console.log('+' + e);
//         });
//     }
// }

// watch(selectedContact, (val, oldVal) => {
//     console.log('watch');
//     connect();
// });

// onMounted(() => {
//     console.log('mounted');
//     connect();
// });
</script>

<template>
    <AppLayout title="Chats">
        <div class="h-[calc(100vh-4rem)] flex justify-between w-full">
            <AsideContainer :contacts.sync="contacts" :selected-contact="selectedContact" @update-selected-contact="updateChoicesReceiver" />

            <div class="w-full p-1 h-full overflow-x-hidden sm:ml-64">
                <EmptyPageContainer v-if="selectedContact == false" >
                    Select a chat to start messaging
                </EmptyPageContainer>
                <ChatMessagesContainer v-else @reload-contacts="getContacts" @toggle-contact="updateChoicesReceiver" :selected-contact.sync="selectedContact" :messages.sync="messages" />
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
