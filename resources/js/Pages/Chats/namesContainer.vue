<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { onMounted } from 'vue';

const cards = ref([]);
const selectedContact = ref([]);

const getContacts = () => {
    axios.get('/contacts').then(response => {
        cards.value = response.data;
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

    <template v-for="( card, index ) in cards" :key="index">
        <li @click="selectContact(card)">
            <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                <img class="w-8 h-8 rounded-full" :src="card.contact_user2.profile_photo_path ?? card.contact_user2.profile_photo_url" alt="">
                <span class="flex-1 ml-3 whitespace-nowrap">{{ card.name }}</span>
                <span class="inline-flex items-center justify-center min-w-3 h-3 py-3 px-2 ml-3 text-sm font-medium text-blue-800 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-300">{{ card.numberOfUnreadChatMessages }}</span>
            </a>
        </li>
    </template>
</template>
