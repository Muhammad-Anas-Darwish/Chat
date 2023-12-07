<script setup>
import ContactsSkeleton from '@/Pages/Chats/Skeletons/ContactsSkeleton.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { onMounted } from 'vue';

function selectContact(data) {
    emit('update-selected-contact', data);
}

const props = defineProps({
    contacts: {
    },
    selectedContact: {
    },
});

const emit = defineEmits(['update-selected-contact']);
</script>

<template>
    <aside id="default-sidebar" class="fixed top-16 left-0 bottom-0 h-[calc(100vh-4rem)] transition-transform  sm:translate-x-0 w-full sm:w-64" :class="{'-translate-x-full': !!(selectedContact)}" aria-label="Sidebar">
        <div class="overflow-y-scroll h-full px-3 py-4 bg-gray-50 dark:bg-gray-800">
            <ul class="space-y-2 font-medium w-full">
                <contactsSkeleton v-if="contacts === null" />
                <template v-else v-for="contact in contacts" :key="contact.contact_user2_id">
                    <li @click="selectContact(contact)">
                        <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group" :class="{ 'bg-gray-700': (contact.contact_user2_id === props.selectedContact.contact_user2_id)}">
                            <img class="w-8 h-8 rounded-full" :src="contact.contact_user2.profile_photo_path ?? contact.contact_user2.profile_photo_url" alt="">
                            <span class="flex-1 ml-3 whitespace-nowrap overflow-hidden truncate">{{ contact.name }}</span>
                            <span v-if="contact.numberOfUnreadChatMessages > 0" class="inline-flex items-center justify-center min-w-3 h-3 py-3 px-2 ml-3 text-sm font-medium text-blue-800 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-300">{{ contact.numberOfUnreadChatMessages }}</span>
                        </a>
                    </li>
                </template>
            </ul>

            <!-- add new contact link -->
            <Link :href="route('contacts.create')" class="absolute bottom-2 right-2 rounded-full bg-gray-700 text-white flex justify-center items-center w-10 h-10"><p class="font-bold">+</p></Link>
        </div>
    </aside>
</template>
