<script setup>
import ContactsSkeleton from '@/Pages/Chats/Skeletons/ContactsSkeleton.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { onMounted } from 'vue';

const contacts = ref(null);
const selectedContact = ref([]);

const getContacts = () => {
    axios.get('/contacts').then(response => {
        contacts.value = response.data;
        //if (contacts.value === [])
          //      contacts.value = true;
    }).catch(error => {
        console.log(error);
    });
};
onMounted(() => {
    getContacts();
});

const emit = defineEmits(['update-selected-contact']);

function selectContact(data) {
    selectedContact.value = data;
    emit('update-selected-contact', data);
}

</script>

<template>
    <Head title="Chats" />

    <contactsSkeleton v-if="contacts === null" />
    <template v-else v-for="contact in contacts" :key="contact.contact_user2_id">
        <li @click="selectContact(contact)">
            <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group" :class="{ 'bg-gray-700' : contact.contact_user2_id === selectedContact.contact_user2_id}">
                <img class="w-8 h-8 rounded-full" :src="contact.contact_user2.profile_photo_path ?? contact.contact_user2.profile_photo_url" alt="">
                <span class="flex-1 ml-3 whitespace-nowrap">{{ contact.name }}</span>
                <span class="inline-flex items-center justify-center min-w-3 h-3 py-3 px-2 ml-3 text-sm font-medium text-blue-800 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-300">{{ contact.numberOfUnreadChatMessages }}</span>
            </a>
        </li>
    </template>

</template>
