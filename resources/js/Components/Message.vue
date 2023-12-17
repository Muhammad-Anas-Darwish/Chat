<script setup>
defineProps({
    color: String,
    message: String,
    time: String,
    classes: String,
    status: String,
});
</script>

<template>
        <p class="relative rounded-lg text-2xl text-white px-2 break-words max-w-[85%] md:max-w-[70%] w-fit" :class="color , classes">
            {{ message }}
            <span class="text-xs">{{ time }}</span>
            <svg v-if="status === 'sent'" class="absolute -bottom-1 -right-2 w-4 h-4 stroke-white bg-gray-500 rounded-full" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" version="1.1" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5">
                <polyline points="2.75 8.75,6.25 12.25,13.25 4.75"/>
            </svg>
            <svg v-if="status === 'read'" class="absolute -bottom-1 -right-2 w-4 h-4 stroke-white bg-gray-500 rounded-full" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" version="1.1" fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5">
                <path d="m1.75 9.75 2.5 2.5m3.5-4 2.5-2.5m-4.5 4 2.5 2.5 6-6.5"/>
            </svg>
        </p>
</template>
