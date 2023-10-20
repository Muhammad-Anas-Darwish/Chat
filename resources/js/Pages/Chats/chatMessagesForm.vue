<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    selectedContact: {
        required: true
    },
});

const form = useForm({
    message: '',
    receiver_id: props.selectedContact['contact_user2_id'],
});

const submit = () => {
    axios.post('/messages/', form)
    .then(res => {
        form.message = "";
        console.log(res);
    })
    .catch(error => {
        console.log(error);
    });
};
</script>

<template>
    <form @submit.prevent="submit">
        <div class="flex gap-4 w-full h-full">
            <TextInput
                v-model="form.message"
                type="text"
                class="mt-1 block w-full outline-none"
                required
                autofocus
            />
            <PrimaryButton class="" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Send
            </PrimaryButton>
        </div>
    </form>
</template>
