<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    selectedContact: {
        required: true
    },
});

const emit = defineEmits(['get-messages']);

const message = ref('');

const submit = () => {
    axios.post('/messages/', {
        message: message.value,
        receiver_id: props.selectedContact['contact_user2_id'],
    })
    .then(res => {
        message.value = "";
        emit('get-messages', '/messages/' + props.selectedContact['contact_user2_id'], true);
        console.log(res);
    })
    .catch(error => {
        console.log(error);
    });
};
</script>

<template>
    <form @submit.prevent="submit">
        <div class="flex gap-1 w-full h-full">
            <TextInput
                v-model="message"
                type="text"
                class="block w-full outline-none"
                required
                autofocus
            />
            <PrimaryButton class="">
                Send
            </PrimaryButton>
        </div>
    </form>
</template>
